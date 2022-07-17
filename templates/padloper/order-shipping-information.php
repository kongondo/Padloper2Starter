<?php

namespace ProcessWire;
// ==================

/**
 * Displays an order's matched shipping rate(s) and handling fees.
 *
 *
 */

/** @var WireArray $orderMatchedShippingRates */
//  $orderMatchedShippingRates
// $shippingRateProperties = [
//     'shippingRate',
//     'shippingRateName',
//     'shippingRateID',
//     'shippingRateCriteriaType',
//     'shippingRateCriteriaMinimum',
//     'shippingRateCriteriaMaximum',
//     'shippingRateDeliveryTimeMinimumDays',
//     'shippingRateDeliveryTimeMaximumDays',
// ];

// @TODO DELETE WHEN DONE; NO LONGER IN USE
// $isMatchedMultipleShippingRates = $orderMatchedShippingRates->count > 1;


?>




<?php

// if we have matched shipping rates for customer country
if ($orderMatchedShippingRates->count) {
	$out = '';
	// ------

	$out .= "<div id='order_matched_shipping_rates_wrapper' class='container mx-auto px-6 mb-8'>";


	// @TODO @UPDATE - DELETE WHEN DONE -> NOW HERE WILL ALWAYS RECEIVE ONE MATCHED SHIPPING RATE (SELECTED RATE) BUT STILL IN WIREARRAY
	// ONLY ONE SHIPPING RATE
	// @TODO IN THIS CASE, SHOULD ALREADY HAVE BEEN ADDED AS SELECTED TO THE ORDER!
	// SO JUST GET THE FIRST (AND ONLY) RATE (WireData) IN THE WireArray
	$matchedShippingRate = $orderMatchedShippingRates->first();

	// if rate has delivery times
	$deliveryTimesMarkup = '';
	if (!empty($matchedShippingRate->shippingRateDeliveryTimeMinimumDays)) {
		$deliveryTimes = sprintf(__('Delivery between %1$d to %2$d days'), $matchedShippingRate->shippingRateDeliveryTimeMinimumDays, $matchedShippingRate->shippingRateDeliveryTimeMaximumDays);
		$deliveryTimesMarkup = 		"<span>{$deliveryTimes}</span><br>";
	}
	// ------
	// @note: in this case, we show the shipping fee itself as it might include taxes and it is final
	// $shippingFee = $padloper->getValueFormattedAsCurrencyForShop($matchedShippingRate->shippingRate);
	$shippingFee = $padloper->getValueFormattedAsCurrencyForShop($order->shippingFee);
	// --------
	$out .=
		"<h2 class='text-xl font-medium leading-6 text-gray-900 mb-4'>" . __("Shipping") . "</h2>" .
		// rate name
		"<span class='block'>" . __("Name") . ": {$matchedShippingRate->shippingRateName}</span>" .
		// delivery times
		$deliveryTimesMarkup .
		// rate / charge / fee
		"<span class='block'>" . __("Fee") . ":  {$shippingFee}</span>";


	// add handling fee if present
	if (!empty($orderHandlingFeeValues->handlingFeeAmount)) {
		$handlingFee = $padloper->getValueFormattedAsCurrencyForShop($orderHandlingFeeValues->handlingFeeAmount);
		// -------
		$out .=
			"<h2 class='text-xl font-medium leading-6 text-gray-900 my-4'>" . __("Handling Fee") . "</h2>" .
			"<span class='block'>" . __("Fee") . ":  {$handlingFee}</span>";
	}

	// add total (handling fee + shipping + taxes included) if present calculated
	// @TODO NEED A CONDITION HERE? isOrderGrandTotalComplete???
	$out .=
		"<h2 class='text-xl font-medium leading-6 text-gray-900 mb-4 mt-8'>" . __("Grand Total") . 	"</h2>";
	// ----------
	$out .= "<div class='block'>";
	if (!empty($isOrderGrandTotalComplete)) {
		$grandTotal = $padloper->getValueFormattedAsCurrencyForShop($orderGrandTotal);
		// -------
		$out .= "<span id='order_grand_total_amount' >" . __("Amount") . ":  {$grandTotal}</span>";
	} else {
		$out .= "<div class='text-indigo-600'><span id='order_grand_total_amount'>" . __("Please select a shipping rate so that a grand total can be computed.")  . "</span></div>";
	}
	// ------
	$out .= "</div>";


	// ---------------
	$out .= "</div>";

	// -----
	echo $out;
}
