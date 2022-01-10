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

function renderSingleProduct($product) {
	/** @var TemplateFile $t */
	$t = getPartialTemplate('single-product-html.php');

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

function renderRelatedProducts($product, $selector = "") {
	$padloper = wire('padloper');
	// ------------
	// find related products
	// @todo: for now, we only 'relate' using categories
	$productCategories = $product->padloper_categories;
	$productCategoriesIDs = $productCategories->implode("|", 'id');
	// -----------
	$selector = "template=product,limit=50,categories={$productCategoriesIDs},id!={$product->id}," . $selector;
	$relatedProducts = $padloper->find($selector);
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
	$out = "";
	if (!empty($cartItem->pad_thumb_url)) {
		// $isSmallClass = empty($isSmall) ? '' : ' h-20 w-20 object-cover rounded';
		$isSmallClass = empty($isSmall) ? '' : ' h-20 w-20 object-cover';
		$out .= "<div class='col-span-1 self-center'>
		<img src='{$cartItem->pad_thumb_url}' alt='{$cartItemTitle}' class='rounded w-full{$isSmallClass}'>
		</div>";
	}
	// ------
	return $out;
}
