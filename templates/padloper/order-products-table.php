<?php

namespace ProcessWire;

// ------------
/**
 * @note: $order is a WireData object
 */


if (!empty($isOrderConfirmed)) {
	// ORDER COMPLETE: GRAND TOTAL (total price + taxes + handling fee + shipping - discounts)
	// @todo:?
	$totalText =  __("Total");
	$totalPrice = $padloper->renderCartPriceAndCurrency($order->totalPrice);
} else {
	// ORDER INCOMPLETE: SUBTOTAL (total price + taxes - discounts)
	// in this case, handling shown separately + choice to select shipping rate is shown
	$totalText =  __("Subtotal");
	$totalPrice = $padloper->renderCartPriceAndCurrency($orderSubtotal);
}



?>
<div id='padloper_order_line_items_tables' class="mt-8 mb-4 w-full">
	<div class="container mx-auto px-6">
		<h3 class="text-xl mb-4"><?php echo __("Order Items"); ?></h3>
		<!-- ORDER LINE ITEMS TABLE -->

		<table class="table-auto w-full text-left whitespace-no-wrap border-b inventory">
			<thead>
				<tr>
					<?php
					// thead > tr > th

					$out = "";

					$theadTrThItems = [
						'item' => __("Item"),
						'unit_price' => __("Unit Price"),
						'quantity' => __("Quantity"),
						'total_price' => __("Total Price"),
					];

					// --------

					$cnt = 1;
					$totalTHItems = count($theadTrThItems);

					foreach ($theadTrThItems as $th) {
						$extraRoundedLeftClasses = $cnt === 1 ? " rounded-tl rounded-bl" : '';
						$extraRoundedRightClasses = $totalTHItems - $cnt === 0 ? " rounded-tr rounded-br" : '';
						// -----------
						$out .= "<th class='px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 uppercase{$extraRoundedLeftClasses}$extraRoundedRightClasses'>{$th}</th>";
						// -----
						$cnt++;
					}

					// ----
					echo $out;
					?>

				</tr>
			</thead>
			<tbody>
				<?php
				// ==============
				// ---------------------------
				// @TODO - CONFIRM IF THESE ARE CART ITEMS VS CONFIRMED ORDER LINE ITEMS!
				$out = "";
				if (!empty($orderLineItems)) {
					foreach ($orderLineItems as $orderLineItem) {
						/** @var WireData $orderLineItem */

						// -----------
						$out .=
							"<tr>" .
							// line item TITLE + tax infor
							"<td class='px-4 py-3'><span>$orderLineItem->productTitle</span>";
						// @TODO do we need this?
						// $orderLineItem->pad_product_notes;
						# ++++++++++++++++++++++++++
						// ---------------
						if ($orderLineItem->taxAmountTotal) {
							$taxTotalAmount = $orderLineItem->taxAmountTotal;
							// @TODO: SORT THIS OUT - USE PADLOPER 2 API!
							$out .= "<small class='tax_description block'>" .
								// @TODO: SORT THIS OUT - USE PADLOPER 2 API
								$orderLineItem->taxName . " " . $padloper->renderCartPriceAndCurrency($taxTotalAmount) . "</small>";
						}
						# ++++++++++++++++++++++++++
						// -----------
						$out .= "</td>";
						// end: title
						// ---------
						// PRICE
						// @TODO: DO WE SHOW PRICE INC OR EX TAX? or does it depend where in checkout we are?
						$out .=  "<td class='px-4 py-3'><span>" . $padloper->renderCartPriceAndCurrency($orderLineItem->unitPrice) .
							"</span></td>" .
							// QUANTITY
							"<td class='px-4 py-3'><span>$orderLineItem->quantity</span></td>" .
							// TOTAL PRICE (of line item)
							"<td class='px-4 py-3 text-gray-900' ><span>" . $padloper->renderCartPriceAndCurrency($orderLineItem->totalPrice) . "</span></td>" .
							"</tr>";
						// ----------
					}
				}
				// ----------
				// line items table rows
				echo $out;
				?>
			</tbody>
		</table>

		<!-- ORDER SUBTOTAL/TOTAL TABLE -->
		<table class="table-auto w-full text-left whitespace-no-wrap mt-8 balance">
			<tbody>
				<tr>
					<td>
						<span class="font-bold mr-1"><?php echo 	$totalText; ?>:</span><span><?php echo $totalPrice; ?></span>
					</td>
				</tr>
				<?php
				// @TODO @KONGONDO AMENDMENT
				// @TODO WORK ON GETTING TAXES
				$out = "";
				foreach ($padloper->getOrderTaxTotals($orderLineItems) as $taxShortName => $value) {
					$out .= "<tr>" .
						"<td><span class='font-bold mr-1'>{$taxShortName}:</span><span>" . $padloper->renderCartPriceAndCurrency($value) . "</span></td>" .
						"</tr>";
				}
				// -----
				// taxes table
				echo $out;
				?>
			</tbody>
		</table>

	</div>
</div>