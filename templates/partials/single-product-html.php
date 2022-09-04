<?php

namespace ProcessWire;


/**
 * @note:
 * - this is just an example of how to display a single product.
 * - example shows how to display both a product with and without variants.
 *  Hence, adapt and/or learn from the example only; it is not a prescription :-)
 */


// -----

// does this product have variants?
/** @var bool $isProductWithVariants */
$isProductWithVariants = $padloper->isProductWithVariants($product);
// @note: alternative check -> product has children with the template 'padloper-product-variant'
// $isProductWithVariants = !empty($product->numChildren("template=padloper-product-variant"));;

// ------------
// INIT SOME VARIABLES
$productVariantsMarkup = '';
$alpineJSInitMarkup = '';
$productImage = "";
$productFirstImage = null;
$brandName =  __("No Brand.");

////////////////////////
// image

// product has images
if ($product->padloper_images->count()) {
	// @TODO SHOW GENERIC IF NO IMAGE?
	$productFirstImage = $product->padloper_images->first();
	// @TODO play with this thumb size as needed!
	$productFirstImageThumb = $productFirstImage->width(400);
}

// ----------------

if ($isProductWithVariants) {
	// +++ PRODUCT WITH VARIANTS +++
	////////////////////////
	// ALPINE JS
	// prepare markup to init alpine.js
	$alpineJSInitMarkup = " x-data='Padloper2DemoData' x-init='initVariants()'";
	$xstore = "\$store.Padloper2DemoStore";

	// ----------------------
	// GET RENDERED MARKUP OF PRODUCT VARIANTS
	$productVariantsMarkup = renderProductVariants($product);
	// ON LOAD if we have a product image, bind it to be displayed by alpine.js
	// @note: in this case, we bind image to alpine.js
	// on load, main product image is the active image
	$productImage = "<img class='lg:w-1/2 w-full h-auto object-cover object-center rounded' :src='{$xstore}.active_image.image_full_url' :alt='{$xstore}.active_image.image_alt'>";
} else {
	// +++ PRODUCT WITHOUT VARIANTS +++
	// IMAGE
	// --------
	//  show the main product image, if any
	// @TODO SHOW GENERIC IF NO IMAGE?
	if (!empty($productFirstImage)) {
		$productImage = "<img class='lg:w-1/2 w-full h-auto object-cover object-center rounded' src='{$productFirstImageThumb->url}' alt='{$product->title}'>";
	}
}

// ##########
$productTitle = $product->title;

// description
$description = $product->padloper_description;

// brand
if ($product->padloper_brand) {
	$brandName = $product->padloper_brand->title;
}

# HTMX
// htmx POST
$post = $config->urls->root;
// htmx Attributes
// @note: hx-trigger will be triggered via js by the event 'padloperfetchupdatedcart' which is a result of a single product (direct or related) getting added to the cart.





?>


<div id='padloper_add_single_product' class="my-8" hx-trigger="padloperfetchupdatedcart delay:500ms" hx-post="<?php echo $post; ?>" hx-target="#padloper_side_cart" hx-vals='{"is_single_product_add": 1}'>
	<div class="container mx-auto px-6" <?php echo $alpineJSInitMarkup; ?>>
		<div class="md:flex md:items-center">
			<div class="lg:w-4/5 flex flex-wrap">
				<?php echo $productImage; ?>

				<div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
					<h2 class="text-sm title-font text-gray-500 tracking-widest uppercase"><?php echo $brandName; ?></h2>
					<h1 class="text-gray-900 text-3xl title-font font-medium mb-1"><?php echo $productTitle; ?></h1>
					<?php
					// bind and display active/selected variant name if product has variant
					if ($isProductWithVariants) {
						echo "<span class='text-indigo-500 mb-1 block' x-text='getSelectedProductVariantName()'></span>";
					}
					?>
					<!-- RATING & REVIEWS -->
					<?php echo renderProductRatingAndReviews();	?>
					<!-- DESCRIPTION -->
					<p class="leading-relaxed"><?php echo $description; ?></p>
					<!-- VARIANTS -->
					<?php echo $productVariantsMarkup; ?>
					<!-- PRICE -->
					<?php echo renderPriceAndAddToCart($product); ?>
				</div>

			</div>
		</div>
		<!-- MORE/RELATED PRODUCTS -->
		<?php echo renderRelatedProducts($product, "limit=4"); ?>

		<!-- div.container -->
	</div>

	<?php
	//  @TODO: MOVE TO SIDE-CART? hidden for now!
	$out = "<div id='cart-summary' class='hidden'>" .
		"<span>Products: <span id='numberOfTitles'>{$numberOfTitles}</span></span>" .
		"<span>Quantity: <span id='totalQty'>{$totalQuantity}</span></span>" .
		// @todo: testing out of bands
		"<span>Price: <span id='totalAmount'>{$totalAmount}</span></span>" .
		"</div>";
	echo $out;
	?>
	<!-- div#padloper_add_single_product -->
</div>