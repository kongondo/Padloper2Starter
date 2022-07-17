<?php

namespace ProcessWire;

// products.php (all products or single if urlSegment 1) template file.

// Primary content
$content = "";

// ----------
// first check if single product view requested
// @note: $beautify=true
$singleProductName = $sanitizer->pageName($input->urlSegment1, $beautify = true);
if (!empty($singleProductName)) {
	// get the product
	$product = $padloper->get("template=product,name={$singleProductName}");
	if ($product->id) {
		$content .= renderSingleProduct($product);
	} else {
		// @todo: 404 instead?
		// throw new Wire404Exception();
		$content .=
			" <div class='container mx-auto px-6'>
								<p class='px-4'>" .
			__("Sorry, we could not find that Product. Please use search function.") .
			"</p>
						</div>";
	}
	$sectionHeader = __("Shop All Products");
	$sectionURL = "/products/";
	$isShowSectionBackArrow = true;
	$title = $product->title;
} else {
	$sectionHeader = __("Products");
	$sectionURL = "";
	// show all (limited, paginaged) products
	// $content = $page->body;
	// get 50 products
	// @todo: sort by popularity? alphabetically?
	$selector = "limit=50";
	$content .= renderProducts($selector);
}
