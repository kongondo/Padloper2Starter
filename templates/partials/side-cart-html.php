<?php

namespace ProcessWire;
// ==================
$cartItems = $padloper->getCart();

$remove =  __("remove");

# HTMX
// htmx POST
$post = $config->urls->root;
// htmx Attributes
// @note: hx-trigger will be triggered via js by the event 'padloperfetchupdatedcart' which is a result of cart items getting updated (increase/decrease).
// @note: not in use for now: spinner; alpine.js; hx-include; hx-indicator
// @todo delay??? delay:500ms ok?
// $htmxAttributes = "hx-trigger='padloperfetchupdatedcart delay:500ms' hx-target='#padloper_bulk_edit_custom_lister' hx-post='{$post}' hx-include='{$hxInclude}' hx-swap='outerHTML' hx-indicator='#padloper_inputfield_selector_spinner' x-data='ProcessPadloperData'";
// $htmxAttributes = "hx-trigger='padloperfetchupdatedcart delay:500ms' hx-post='{$post}' hx-target=''";



?>
<!-- SIDE CART -->
<section id='padloper_side_cart'>

	<div :class="getIsCartOpen() ? 'translate-x-0 ease-out' : 'translate-x-full ease-in'" class="fixed right-0 top-0 max-w-xs w-full h-full px-6 py-4 transition duration-300 transform overflow-y-auto bg-white border-l-2 border-gray-300 z-30">
		<div class="flex items-center justify-between">
			<h3 class="text-2xl font-medium text-gray-700"><?php echo __("Your cart"); ?><span id="padloper_side_cart_spinner" class="fa fa-fw fa-spin fa-spinner ml-1 mt text-indigo-600 text-sm font-medium -mt-16 htmx-indicator"></span></h3>
			<button @click="setIsCartOpen" class="text-gray-600 focus:outline-none">
				<svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
					<path d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>
		<hr class="my-3">

		<?php
		$out = "";
		if (!empty($cartItems)) {
			// CSRF @see htmx below and above
			$out .= $session->CSRF->renderInput();
			// ------------
			foreach ($cartItems as $cartItem) {
				// $itemPriceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($cartItem->pad_price);
				$totalItemPriceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($cartItem->pad_price_total);

				$out .=
					"<div class='flex justify-between mt-6'>
					<div class='flex'>" .
					// get product image if available
					getCartItemThumbURL($cartItem, $cartItem->pad_title, $isSmall = true) .
					"<div class='mx-3'>
							<h3 class='text-sm text-gray-600'>{$cartItem->pad_title}</h3>" .
					"<div class='flex items-center mt-2'>" .
					// INCREASE ITEM AMOUNT BUTTON
					// "<button class='padloper_cart_item_updater text-gray-500 focus:outline-none focus:text-gray-600' hx-post='{$post}' hx-swap='none'>
					"<button class='padloper_cart_item_amount_updater text-gray-500 focus:outline-none focus:text-gray-600' data-cart-item-id='{$cartItem->id}' data-cart-item-quantity='{$cartItem->quantity}' data-updater-type='increase'>
													<svg class='h-5 w-5' fill='none' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' viewBox='0 0 24 24' stroke='currentColor'>
														<path d='M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'></path>
													</svg>
												</button>" .
					//  ITEM QUANTITY IN CART
					"<span class='text-gray-700 mx-2'>{$cartItem->quantity}</span>" .
					// DECREASE ITEM AMOUNT BUTTON
					// "<button class='text-gray-500 focus:outline-none focus:text-gray-600'>
					"<button class='padloper_cart_item_amount_updater text-gray-500 focus:outline-none focus:text-gray-600' data-cart-item-id='{$cartItem->id}' data-cart-item-quantity='{$cartItem->quantity}' data-updater-type='decrease'>
									<svg class='h-5 w-5' fill='none' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' viewBox='0 0 24 24' stroke='currentColor'>
										<path d='M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'></path>
									</svg>
								</button>" .
					// remove item from cart
					// @note: data-cart-item-quantity=0 to force final calculated value to negative, hence 0
					"<span class='padloper_cart_item_amount_remover text-gray-600 ml-4 cursor-pointer' title='{$remove}' data-cart-item-id='{$cartItem->id}' data-cart-item-quantity='0' data-updater-type='decrease'>x</span>
							</div>

						</div>
						</div>
					<span class='text-gray-600'>{$totalItemPriceAsCurrency}</span>
				</div>";
			}
			// checkout button
			$out .=
				"<a class='flex items-center justify-center mt-4 mb-4 px-3 py-2 bg-indigo-500 text-white text-sm uppercase font-medium rounded hover:bg-indigo-600 focus:outline-none focus:bg-blue-500' href='/checkout/'>
					<span>" .
				__('Checkout') .
				"</span>" .
				"<svg class='h-5 w-5 mx-2' fill='none' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' viewBox='0 0 24 24' stroke='currentColor'>
					<path d='M17 8l4 4m0 0l-4 4m4-4H3'></path>
					</svg>
				</a>";
		} else {
			$out .= "<p>" .  __("Your cart is empty.") . "</p>";
		}
		echo $out;


		/*	<div class="mt-8">
		<form class="flex items-center justify-center">
			<input class="form-input w-48" type="text" placeholder="Add promocode">
			<button class="ml-3 flex items-center px-3 py-2 bg-indigo-500 text-white text-sm uppercase font-medium rounded hover:bg-indigo-600 focus:outline-none focus:bg-blue-500">
				<span>Apply</span>
			</button>
		</form>
	</div> */

		?>
		<form action="" id="padloper_cart_updater" hx-trigger="padloperfetchupdatedcart delay:500ms" hx-post="<?php echo $post; ?>" hx-target="#padloper_side_cart" hx-indicator="#padloper_side_cart_spinner">
			<input type="hidden" id="padloper_cart_update_product_id" name="padloper_cart_update_product_id" value="0">
			<input type="hidden" id="padloper_cart_update_product_quantity" name="padloper_cart_update_product_quantity" value="0">
		</form>
	</div>
</section>