<?php

namespace ProcessWire;

// ------------
// get IDs of products in cart
// we want to check if they allow product customisation by customer
$productIDs = array_column($cartItems, 'product_id');
$productIDsSelector = implode("|", $productIDs);
/** @var array $productIDsAllowCustomisation */
$productIDsAllowCustomisation = $padloper->findRaw("id={$productIDsSelector},product_is_customisable!=''", 'id');
// -----------------
// @note: shipping and taxes not included at this stage!
$subtotal = $padloper->getValueFormattedAsCurrencyForShop($padloper->getOrderTotalAmount());
$pricesInclusiveOrExclusiveOfTaxStr = "";
if (!empty($padloper->isPricesIncludeTaxes())) {
	$pricesInclusiveOrExclusiveOfTaxStr  = __("inc tax");
} else {
	$pricesInclusiveOrExclusiveOfTaxStr  = __("ex tax");
}

$isHaveOrderLineItemsPages = false;

$isHaveCustomisableProducts = !empty($productIDsAllowCustomisation);

?>
<!-- CHECKOUT FORM PARTIAL: ORDER SUMMARY  -->
<div class="px-4 sm:px-0 py-5">
	<!-- HEADER -->
	<h3 class="text-lg font-medium leading-6 text-gray-900"><?php echo __("Order Summary"); ?></h3>
	<?php
	// CART ITEMS
	$out = "";
	if (count($cartItems)) {
		// =====
		// IF HAVE CUSTOMISABLE PRODUCTS -> check if we have order items
		// i.e., basket amended after checkout, meaning order line items were created
		if (!empty($isHaveCustomisableProducts)) {
			// check if we have order line item pages
			$orderLineItemsPages = $padloper->getOrderLineItemsPages();

			if (!empty($orderLineItemsPages->count())) {
				$isHaveOrderLineItemsPages = true;
			}
		}

		#################
		// ----------------
		$out .= "<ul class='py-6 border-b space-y-6 px-8'>";
		foreach ($cartItems as $cartItem) {
			$itemPriceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($cartItem->pad_price);
			$totalItemPriceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($cartItem->pad_price_total);
			// @TODO: ADD IMAGE USING GET RAW?
			$out .=
				"<li class='grid grid-cols-6 gap-2 border-b-1'>" .
				// get product image if available
				getCartItemThumbURL($cartItem, $cartItem->pad_title) .
				// ---------
				"<div class='flex flex-col col-span-3 pt-2'>" .
				"<span class='text-gray-600 text-md font-semibold'>{$cartItem->pad_title}</span>" .
				"</div>" .
				"<div class='col-span-2 pt-3'>" .
				"<div class='flex items-center space-x-2 text-sm justify-between'>" .
				"<span class='text-gray-400'>{$cartItem->quantity} x $itemPriceAsCurrency</span>" .
				"<span class='font-semibold inline-block'>{$totalItemPriceAsCurrency}</span>" .
				"</div>" .
				"</div>";
			// add textarea to customise product if product is customisable
			if (in_array($cartItem->product_id, $productIDsAllowCustomisation)) {
				$productID = $cartItem->product_id;
				$existingProductCustomisationInfo = "";
				// if we have existing order line items, see if we have saved product customisation values
				if (!empty($isHaveOrderLineItemsPages)) {
					// get the order line item page that corresponds to this product ID
					$orderLineItemPage = $orderLineItemsPages->get("padloper_order_line_item.productID={$productID}");
					if (!empty($orderLineItemPage)) {
						$existingProductCustomisationInfo = $orderLineItemPage->get('product_customise_details');
					}
				}
				// ---------
				// append textarea to collect product customisation info from customer
				$out .= renderCheckoutFormOrderProductCustomisation($productID, $cartItem->pad_title, $existingProductCustomisationInfo);
			}
			// ----------
			$out .= "</li>";
		}
		// ------
		$out .= "	</ul>";
	}

	echo $out;


	?>

	<div class="px-8 border-b">
		<!-- SUBTOTAL -->
		<div class="flex justify-between py-4 text-gray-600">
			<span><?php
						echo __("Subtotal");
						echo " ($pricesInclusiveOrExclusiveOfTaxStr)";
						?></span>
			<span class="font-semibold"><?php echo $subtotal; ?></span>
		</div>
		<!-- SHIPPING -->
		<div class="flex justify-between py-4 text-gray-600">
			<span><?php echo __("Shipping"); ?></span>
			<!-- @todo -->
			<span class="text-sm"><?php echo __("Calculated at confirmation."); ?></span>
		</div>
	</div>
</div>