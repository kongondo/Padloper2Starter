<?php

namespace ProcessWire;

// home.php (homepage) template file.

// HANDLE HTMX REQUESTS
if ($config->ajax) {
	$id = (int) $input->post->padloper_cart_update_product_id;
	$quantity = (int) $input->post->padloper_cart_update_product_quantity;

	// @TODO HANDLE ERRORS, NOT SENT, ETC!
	// if single product (direct or related product) operation VS side-cart increase/decrease single item amount
	$isSingleProductAdd = !empty((int) $input->post->is_single_product_add);

	if (empty($isSingleProductAdd)) {
		// SIDE CART AMOUNT CHANGES TRIGGERED
		// we need to update the session cart first
		// 1. update cart
		// @note: in this case, only one cart item at a time is getting updated
		$updatedCartProduct = [$id => $quantity];
		// bd($updatedCartProduct, __METHOD__ . ': $updatedCartProduct at line #' . __LINE__);
		// @todo: expected foreach ($updatedCartProduct as $id => $cartProductQuantity)
		$padloper->updateCart($updatedCartProduct, $rem_products = null, $isRedirect = false);
	}

	// 2. updated cart content to reload the cart
	$out = renderSideCart();
	// 3. updated unique titles
	// @note: oob for top-navigation!
	$cartUniqueTitles = $padloper->cart->getNumberOfTitles();
	$out .=
		"<span id='numberOfTitles' hx-swap-oob='true'>{$cartUniqueTitles}</span>";
	// -----------
	echo $out;
	// exit;
	$this->halt();
}


// ---------------
// USUAL CONTENT

// Primary content is the page body copy
$content = "";
// $content = $page->body;
// get 12 latest products
$selector = "limit=12,sort=-created";
$content .= renderProducts($selector);
$isShowCarousel = true;
