<?php

namespace ProcessWire;


/**
 * @note:
 * - variants markup for products that have variants.
 * - for use with the template partial 'single-product-html.php'
 *  Adapt and/or learn from the example only; it is not a prescription :-)
 */

//////////////////////////////
// ALPINE JS
// prepare markup to init alpine.js
$xstore = "\$store.Padloper2DemoStore";

// --------
$mainProductImageThumbURL = "";
$mainProductImageThumbBigURL = "";
$mainProductImageFullURL = "";
if (!(empty($product->padloper_images->count()))) {
	$firstImage = $product->padloper_images->first();
	$mainProductImageThumb = $firstImage->width(260);
	$mainProductImageThumbBig = $firstImage->width(400);
	// ------------
	$mainProductImageThumbURL = $mainProductImageThumb->url;
	$mainProductImageThumbBigURL = $mainProductImageThumbBig->url;
	$mainProductImageFullURL = $firstImage->url;
} else {
	// USE GENERIC IMAGE-NOT-FOUND
	$genericImagePath = getNoImageFoundIcon('image-not-found.svg');
	$mainProductImageThumbURL =	$genericImagePath;
	$mainProductImageThumbBigURL = $genericImagePath;
	$mainProductImageFullURL = $genericImagePath;
}

$stock = $product->padloper_product_stock;
$price = $stock->price;
$priceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($price);


$mainProductForJavaScript = [
	'title' => $product->title,
	'price' => $priceAsCurrency,
	'image_thumb_url' => $mainProductImageThumbURL,
	'image_thumb_big_url' => $mainProductImageThumbBigURL,
	'image_full_url' => $mainProductImageFullURL,
	'image_alt' => $product->title,
];

///////////////////////////////////

// all variants in product
/** @var array $variants */
$variants = getProductVariants($product);
// bd($product, __METHOD__ . ': $product at line #' . __LINE__);
// bd($variants, __METHOD__ . ': $variants at line #' . __LINE__);

/** @var PageArray $attributes */
$attributes = $product->padloper_product_attributes;
// $numberOfOptions = $attributes->count();
// d($numberOfOptions, __METHOD__ . ': $numberOfOptions at line #' . __LINE__);
// $groupingNumber = $numberOfOptions - 1;
// d($groupingNumber, __METHOD__ . ': $groupingNumber at line #' . __LINE__);
$attributesNames = $attributes->explode('name');
// bd($attributes, __METHOD__ . ': $attributes at line #' . __LINE__);
// bd($attributesNames, __METHOD__ . ': $attributesNames at line #' . __LINE__);

$attributesForJavaScript = $attributes->explode(['id', 'title']);

///////////////////
// prepare attributes and their options
$attributesAndTheirOptions = [];
$allOptionsForJavaScript = [];
$variantsForJavaScript = [];
foreach ($variants as $variant) {

	// ---------------
	// get options that make up this variant
	/** @var PageArray $variantOptions */
	$variantOptions = $variant->padloper_product_attributes_options;

	// -----------
	// IF VARIANT HAS image get it -> else get from similar option
	/** @var Pageimage $variantImage */
	$variantImage = getVariantFirstImage($variant);
	// bdb($variantImage, __METHOD__ . ': $variantImage at line #' . __LINE__);
	if (empty($variantImage)) {
		$optionsIDs = $variantOptions->explode('id');
		/** @var array $variantImage */
		$variantImage = getVariantImageFromOption($optionsIDs, $variants, $product);
	}
	// bdb($variantImage, __METHOD__ . ': $variantImage - FINAL - at line #' . __LINE__);
	//------
	// @TODO OK?
	// INIT variant option image with empties
	$variantOptionImage = [
		'variant_image_thumb_url' => '',
		'variant_image_thumb_big_url' => '',
		'variant_image_full_url' => ''
	];
	if (!empty($variantImage)) {
		$variantOptionImage = [
			'variant_image_thumb_url' => $variantImage->height(260)->url,
			'variant_image_thumb_big_url' => $variantImage->height(400)->url,
			'variant_image_full_url' => $variantImage->url
		];
	} else {
		// USE GENERIC IMAGE-NOT-FOUND
		$genericImagePath = getNoImageFoundIcon('image-not-found.svg');
		$variantOptionImage = [
			'variant_image_thumb_url' => $genericImagePath,
			'variant_image_thumb_big_url' => $genericImagePath,
			'variant_image_full_url' => $genericImagePath
		];
	}
	// attribute-option pairs for JavaScript
	$attributeOptionPairsForJavaScript = [];
	// ------
	// prepare options for each attribute
	foreach ($attributes as $attribute) {

		/** @var Page $attribute */
		// $attributesAndTheirOptions[$attribute->id][] = $variantOptions->get("parent=$attribute");
		/** @var Page $variantOption */
		$variantOption = $variantOptions->get("parent=$attribute");
		// --------
		$attributePrefix = "{$attribute->id}_{$variantOption->id}";
		// db($variantOption, __METHOD__ . ': $variantOption at line #' . __LINE__);
		$attributesAndTheirOptions[$attribute->id][$variantOption->id] = [
			'option_id' => $variantOption->id,
			'option_title' => $variantOption->title,
			'option_attribute_id' => $attribute->id,
			'option_attribute_title' => $attribute->title,
			'option_image_thumb' => $variantOptionImage['variant_image_thumb_url'],
			'option_big_image_thumb' => $variantOptionImage['variant_image_thumb_big_url'],
			'option_image_full' => $variantOptionImage['variant_image_full_url'],
		];
		$attributeOptionPairsForJavaScript[$attribute->id] = $variantOption->id;
		// ---------
		$allOptionsForJavaScript[$variantOption->id] = $variantOption->title;
	}

	$stock = $variant->padloper_product_stock;
	$price = $stock->price;
	$priceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($price);

	// ------------
	// PREPARE VARIANTS INFO FOR JAVASCRIPT
	$variantsForJavaScript[] = [
		'variant_id' => $variant->id,
		'variant_title' => $variant->title,
		'price' => $priceAsCurrency,
		'image_thumb_url' => $variantOptionImage['variant_image_thumb_url'],
		'image_thumb_big_url' => $variantOptionImage['variant_image_thumb_big_url'],
		'image_full_url' => $variantOptionImage['variant_image_full_url'],
		'image_alt' => $variant->title,
		'variant_attribute_option_pairs' => $attributeOptionPairsForJavaScript
	];
	// bd($attributeOptionPairsForJavaScript, __METHOD__ . ': $attributeOptionPairsForJavaScript at line #' . __LINE__);
}

// db($attributesAndTheirOptions, __METHOD__ . ': $attributesAndTheirOptions at line #' . __LINE__);
// bdb($attributesAndTheirOptions, __METHOD__ . ': $attributesAndTheirOptions at line #' . __LINE__);
// bdb($allOptionsForJavaScript, __METHOD__ . ': $allOptionsForJavaScript at line #' . __LINE__);

$selectVariantForPriceText = __("Select variant for price");

// ---------------------


?>

<!-- VARIANTS MARKUP -->
<div class="mt-6 pb-5 border-b-2 border-gray-100 mb-5">
	<?php
	$out = "";

	foreach ($attributesAndTheirOptions as $attributeID => $options) {

		$attribute = $attributes->get("id={$attributeID}");
		//  single attribute
		$out .=
			"<div class='padloper_2_demo_attribute_wrapper'>" .
			//  selected attribute option
			"<div class='padloper_2_demo_attribute_and_option_name'>" .
			"<span class='mr-1'>{$attribute->title}:</span>" .
			"<span class='font-bold' x-text='getSelectedAttributeOptionName({$attributeID})'></span>" .
			"</div>" .
			//  attribute options selector markup
			"<div class='padloper_2_demo_attribute_options_wrapper mt-3 mb-6 pb-5 border-b border-gray-100'>" .
			// attribute options list
			"<ul class='flex flex-wrap'>";

		// options selections
		foreach ($options as $option) {
			$optionTitle = $option['option_title'];
			$optionID = $option['option_id'];
			$imageThumb = "";
			$src = "";
			if (in_array('colour', $attributesNames)) {
				// bd($attributesNames, __METHOD__ . ': $attributesNames HAVE COLOUR at line #' . __LINE__);
				if ($attribute->name === 'colour') {
					$src = $option['option_image_thumb'];
					$imageThumb  = "<img src='{$src}' alt='{$optionTitle}'>";
				}
			} else {
				// bd($attributesNames, __METHOD__ . ': $attributesNames NO COLOUR at line #' . __LINE__);
				$src = $option['option_image_thumb'];
				$imageThumb  = "<img src='{$src}' alt='{$optionTitle}'>";
			}

			// ========
			// LIST ITEM FOR A VARIANT ATTRIBUTE OPTION
			/*
				@note:
				-  differences in padding and bound classes for list item with OR without image (src)
				- @click: we use the method setSelectedOptionForAttribute() to set the $optionID for that $attributeID in alpine.js
				- @class: we bind selected option by checking if the object 'selected_attribute_option_pairs' attribute_id key equals the option_id
			*/
			if (!empty($src)) {
				//  option with image src
				$out .= "<li class='m-2 p-0.5 cursor-pointer border rounded' :class='{$xstore}.selected_attribute_option_pairs[{$attributeID}]=={$optionID} ? `border-indigo-400` : `border-gray-200`' @click='setSelectedOptionForAttribute({$optionID},{$attributeID})'>";
				$out .= "<div class='bg-cover w-24 h-20' style='background-image: url({$src})'></div>";
			} else {
				// option without img src
				$out .= "<li class='rounded border border-gray-300 m-2 p-3 cursor-pointer' :class='{$xstore}.selected_attribute_option_pairs[{$attributeID}]=={$optionID} ? `bg-indigo-200` : ``' @click='setSelectedOptionForAttribute({$optionID},{$attributeID})'>" .
					$optionTitle;
			}

			$out .= "<input type='hidden' value='{$optionID}'>" .
				"</li>";
		} // END: foreach($options as $option

		// close wrappers and parents
		$out .=
			"</ul>" .
			"</div>" . // div.padloper_2_demo_attribute_options_wrapper
			// end: div.padloper_2_demo_attribute_wrapper
			"</div>";
	}
	echo $out;


	// ########################

	// POPULATE VARIANTS INFO FOR JavaScript
	$variantsInfo = [
		// main product info
		'main_product' => $mainProductForJavaScript,
		// ****************
		// attributes info
		'attributes' => $attributesForJavaScript,
		// ****************
		// options info
		'options' => $allOptionsForJavaScript,
		// ****************
		// variants info
		// @note: 'images' can also be used for 'colour' selection!
		'all_variants' => $variantsForJavaScript,
		// ****************
		// other
		'no_option_selection' => __("Select an option"),
		'no_variant_selection' => __("Please select a variant"),
		'no_variant_selection_price' => $selectVariantForPriceText,

	];


	// -------------
	$script = "<script>PadloperDemoVariants = " . json_encode($variantsInfo) . ';</script>';
	echo $script;
	?>
</div>