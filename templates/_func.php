<?php

namespace ProcessWire;

/**
 * /site/templates/_func.php
 *
 * Example of shared functions used by template files
 *
 * This file is currently included by _init.php
 *
 * FUN FACT: This file is identical to the one in the NON-multi-language
 * version of this site profile (site-default). In fact, it's rare that
 * one has to think about languages when developing a multi-language
 * site in ProcessWire.
 *
 */

/**
 * Given a group of pages, render a simple <ul> navigation
 *
 * This is here to demonstrate an example of a simple shared function.
 * Usage is completely optional.
 *
 * @param PageArray $items
 * @return string
 *
 */
function renderNav(PageArray $items) {

	// $out is where we store the markup we are creating in this function
	$out = '';

	// cycle through all the items
	foreach ($items as $item) {

		// render markup for each navigation item as an <li>
		if ($item->id == wire('page')->id) {
			// if current item is the same as the page being viewed, add a "current" class to it
			$out .= "<li class='current'>";
		} else {
			// otherwise just a regular list item
			$out .= "<li>";
		}

		// markup for the link
		$out .= "<a href='$item->url'>$item->title</a> ";

		// if the item has summary text, include that too
		if ($item->summary) $out .= "<div class='summary'>$item->summary</div>";

		// close the list item
		$out .= "</li>";
	}

	// if output was generated above, wrap it in a <ul>
	if ($out) $out = "<ul class='nav'>$out</ul>\n";

	// return the markup we generated above
	return $out;
}



/**
 * Given a group of pages, render a <ul> navigation tree
 *
 * This is here to demonstrate an example of a more intermediate level
 * shared function and usage is completely optional. This is very similar to
 * the renderNav() function above except that it can output more than one
 * level of navigation (recursively) and can include other fields in the output.
 *
 * @param array|PageArray $items
 * @param int $maxDepth How many levels of navigation below current should it go?
 * @param string $fieldNames Any extra field names to display (separate multiple fields with a space)
 * @param string $class CSS class name for containing <ul>
 * @return string
 *
 */
function renderNavTree($items, $maxDepth = 0, $fieldNames = '', $class = 'nav') {

	// if we were given a single Page rather than a group of them, we'll pretend they
	// gave us a group of them (a group/array of 1)
	if ($items instanceof Page) $items = array($items);

	// $out is where we store the markup we are creating in this function
	$out = '';

	// cycle through all the items
	foreach ($items as $item) {

		// markup for the list item...
		// if current item is the same as the page being viewed, add a "current" class to it
		$out .= $item->id == wire('page')->id ? "<li class='current'>" : "<li>";

		// markup for the link
		$out .= "<a href='$item->url'>$item->title</a>";

		// if there are extra field names specified, render markup for each one in a <div>
		// having a class name the same as the field name
		if ($fieldNames) foreach (explode(' ', $fieldNames) as $fieldName) {
			$value = $item->get($fieldName);
			if ($value) $out .= " <div class='$fieldName'>$value</div>";
		}

		// if the item has children and we're allowed to output tree navigation (maxDepth)
		// then call this same function again for the item's children
		if ($item->hasChildren() && $maxDepth) {
			if ($class == 'nav') $class = 'nav nav-tree';
			$out .= renderNavTree($item->children, $maxDepth - 1, $fieldNames, $class);
		}

		// close the list item
		$out .= "</li>";
	}

	// if output was generated above, wrap it in a <ul>
	if ($out) $out = "<ul class='$class'>$out</ul>\n";

	// return the markup we generated above
	return $out;
}

function renderProducts($selector = "") {
	$padloper = wire('padloper');
	$selector = "template=product,limit=50," . $selector;
	$products = $padloper->find($selector);
	/** @var TemplateFile $t */
	$t = getPartialTemplate('products-grid-html.php');
	$t->set('products', $products);
	// -----------
	$out = $t->render();

	// --------
	return $out;
}

function renderSingleProduct($product, $templateFileName = '') {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('single-product-html.php');
	// $t = getPartialTemplate($templateFileName);

	$padloper = wire('padloper');
	$cart = $padloper->cart;
	// ------------------
	$numberOfTitles = $cart->getNumberOfTitles();
	$totalAmount = $cart->getTotalAmount();
	$totalQuantity = $cart->getQuantity();

	$t->set('product', $product);
	$t->set('numberOfTitles', $numberOfTitles);
	$t->set('totalAmount', $totalAmount);
	$t->set('totalQuantity', $totalQuantity);

	// -------------
	$out = $t->render();
	// --------
	return $out;
}

function renderProductVariants(Page $product) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('product-variants-html.php');
	// -----------
	$t->set('product', $product);
	// -------------
	$out = $t->render();
	// --------
	return $out;
}

function renderProductRatingAndReviews() {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('product-rating-and-reviews-html.php');
	// -------------
	$out = $t->render();
	// --------
	return $out;
}

function renderPriceAndAddToCart($product) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('product-price-and-add-to-cart-html.php');
	$t->set('product', $product);
	// -------------
	$out = $t->render();
	// --------
	return $out;
}

/**
 * Find all a product's variants using $pages API.
 *
 *
 * @param Page $product Product page whose variants to return
 * @return PageArray $variants PagesArray of variants.
 */
function getProductVariants(Page $product) {
	$padloper = wire('padloper');
	$variants = $padloper->find("template=variant,parent={$product}");
	//---------
	return $variants;
}

/**
 * Find all a product's variants using Find Raw.
 *
 * Use findRaw() and return id, title, stock and attribute options fields.
 *
 * @param Page $product Product page whose variants to return
 * @return array $variantsRaw Array of values for variants.
 */
function getProductVariantsRaw(Page $product) {
	$padloper = wire('padloper');
	$fields = ['id', 'title', 'padloper_product_stock' => 'stock', 'padloper_product_attributes_options' => 'options'];
	$variantsRaw = $padloper->findRaw("template=variant,parent={$product}", $fields);
	//---------
	return $variantsRaw;
}

/**
 * Format a findRaw of  a product's variants.
 *
 * Return formatted id, title, stock and attribute options fields.
 *
 * @param array $variantsRaw A findRaw variants array to format.
 * @return array $variants Array of formatted values for variants.
 */
function getProductVariantsFormattedInfo(array $variantsRaw) {
	$padloper = wire('padloper');
	// ------------
	// format the values into a flat array to easily pass to JavaScript
	$variants = [];
	foreach ($variantsRaw as $variantRaw) {
		$stock = $variantRaw['stock'];
		$options = $variantRaw['options'];
		$optionsIDs = (int)implode("", $options);
		$variant = [
			'variant_id' => (int)$variantRaw['id'],
			'name' => $variantRaw['title'],
			'sku' => $stock['data'],
			'price' => $stock['price'],
			'compare_price' => $stock['compare_price'],
			'price_with_currency' => $padloper->getValueFormattedAsCurrencyForShop($stock['price']),
			'compare_price_with_currency' => $padloper->getValueFormattedAsCurrencyForShop($stock['compare_price']),
			'options_ids' => $optionsIDs

		];
		// -------
		// add variant to variants array
		$variants[] = $variant;
	}
	//---------
	return $variants;
}

function getVariantFirstImage(Page $variant) {
	$variantFirstImage = null;
	if (!empty($variant->padloper_images->count())) {
		$variantFirstImage = $variant->padloper_images->first();
	}
	// -----
	return $variantFirstImage;
}

function getVariantImageFromOption(array $optionIDs, $variants, $product = null) {
	$optionIDsSelector = implode("|", $optionIDs);
	$variantFirstImage = null;
	$variantWithImage = $variants->get("padloper_images.count>0, padloper_product_attributes_options={$optionIDsSelector}");
	if (!empty($variantWithImage)) {
		$variantFirstImage = $variantWithImage->padloper_images->first();
	} elseif (!empty($product)) {
		if (!empty($product->padloper_images->count())) {
			$variantFirstImage = $product->padloper_images->first();
		}
	}
	return $variantFirstImage;
}

function getNoImageFoundIcon($iconFileName = null) {
	if (empty($iconFileName)) {
		$iconFileName = "no-image-icon.png";
	}
	$config = wire('config');
	$iconFilePath = null;
	if (is_file($config->paths->templates . "images/" . $iconFileName)) {
		$iconFilePath = $config->urls->templates . "images/" . $iconFileName;
	}
	return $iconFilePath;
}

function renderRelatedProducts($product, $selector = "") {
	$padloper = wire('padloper');
	// ------------
	// find related products
	// @todo: for now, we only 'relate' using categories
	$productCategories = $product->padloper_categories;
	$relatedProducts = null;
	if (!empty($productCategories->count())) {
		$productCategoriesIDs = $productCategories->implode("|", 'id');
		// -----------
		$selector = "template=product,limit=50,categories={$productCategoriesIDs},id!={$product->id}," . $selector;
		$relatedProducts = $padloper->find($selector);
	}
	/** @var TemplateFile $t */
	$t = getPartialTemplate('related-products-html.php');
	$t->set('relatedProducts', $relatedProducts);
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCategories($selector = "") {
	$padloper = wire('padloper');
	// @note - WE WANT CATEGORIES WITH AT LEAST ONE PUBLISHED PRODUCT
	// we use owner selector for this!
	// @see: https://processwire.com/blog/posts/processwire-3.0.95-core-updates/
	$selector = "template=category, categories.owner.status!=unpublished,limit=50," . $selector;
	$categories = $padloper->find($selector);
	/** @var TemplateFile $t */
	$t = getPartialTemplate('categories-grid-html.php');
	$t->set('categories', $categories);
	// -----------
	$out = $t->render();

	// --------
	return $out;
}

function renderFooter() {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('footer-html.php');
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCarousel() {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('carousel-html.php');
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderSideCart() {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('side-cart-html.php');
	// $cart = $padloper->cart;
	// $cartItems = $cart->getCart();
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderTopNavigation() {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('top-navigation-html.php');
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCheckoutForm($formErrors = [], $previousValues = null) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('checkout-form-html.php');
	$padloper = wire('padloper');
	$cartItems = $padloper->getCart();
	$t->set('cartItems', $cartItems);
	$t->set('formErrors', $formErrors);
	// @note: if not empty will be WireInputData from $input->post of values submitted with the form
	$t->set('previousValues', $previousValues);
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCheckoutFormOrderSummary($cartItems) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('checkout-form-order-summary-html.php');
	$t->set('cartItems', $cartItems);
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCheckoutFormOrderProductCustomisation($productID, $productTitle, $existingProductCustomisationInfo = null) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('checkout-form-customisable-product-details-html.php');
	$t->set('productID', $productID);
	$t->set('productTitle', $productTitle);
	$t->set('existingProductCustomisationInfo', $existingProductCustomisationInfo);
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

function renderCheckoutFormCustomerDetails($formErrors, $previousValues) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('checkout-form-customer-details-html.php');
	$padloper = wire('padloper');
	// @TODO: SET LOGGED IN USER AS CUSTOMER?
	$shippingCountries = $padloper->getShippingCountries();
	$t->set('shippingCountries', $shippingCountries);
	$t->set('formErrors', $formErrors);
	// @note: if not empty will be WireInputData from $input->post of values submitted with the form
	$t->set('previousValues', $previousValues);
	// -----------
	$out = $t->render();
	// --------
	return $out;
}

// -----------
function getPartialTemplate($file) {
	$config = wire('config');
	$templatePath = $config->paths->templates . "partials/" . $file;
	return new TemplateFile($templatePath);
}

function getCartItemThumbURL($cartItem, $cartItemTitle, $isSmall = false) {
	$thumbURL = null;
	$out = "";
	/** @var stdClass $cartItem */
	if (!empty($cartItem->pad_thumb_url)) {
		$thumbURL = $cartItem->pad_thumb_url;
	} elseif (!empty($cartItem->pad_is_variant)) {
		// if item is a variant and it doesn't have a thumb
		// we try to get one from its sibling
		// @note: this is a specific example; you will have to adjust this to your shop setup or even not use this approach
		// in this example, thumbs are stored only in one variant per group of similar variants based on their colour
		// e.g. all black tshirts don't each need a thumb; only one (first one) gets the thumb
		// @see the partial template file 'product-variants-html.php' for the assumptions made
		// -------------
		$padloper = wire('padloper');
		// first, get the
		$variant = $padloper->get("id={$cartItem->product_id}");
		// GET THE OPTIONS that make up this variant
		// we will then extract the 'colour' info from them
		/** @var PageArray $options */
		$options = $variant->padloper_product_attributes_options;
		// COLOUR + SIZE OPTIONS ->
		// @TODO @NOTE: HARDCODED! you need to adjust this for your shop as required!
		// @TODO: you will also need to account for multilingual setups
		// we get the option whose parent name is colour
		# colour option #
		/** @var Page $colourOption */
		$colourOption = $options->get("parent.name=colour");
		// ------------
		// finally, get the sibling with the image
		/** @var Page $siblingVariant */
		$siblingVariant = $padloper->get("template=variant,parent={$cartItem->pad_variant_parent_id},images.count>0,options={$colourOption}");
		if ($siblingVariant->id) {
			/** @var Pageimage $image */
			$variantImage = $siblingVariant->padloper_images->first();
			$variantThumb = $variantImage->height(260);
			$thumbURL = $variantThumb->url;
		}
	}
	// -----
	if (!empty($thumbURL)) {
		// $isSmallClass = empty($isSmall) ? '' : ' h-20 w-20 object-cover rounded';
		$isSmallClass = empty($isSmall) ? '' : ' h-20 w-20 object-cover';
		$out .= "<div class='col-span-1 self-center'>
				<img src='{$thumbURL}' alt='{$cartItemTitle}' class='rounded w-full{$isSmallClass}'>
				</div>";
	}
	// ------
	return $out;
}
