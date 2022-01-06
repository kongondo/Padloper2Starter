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

$isMatchedMultipleShippingRates = $orderMatchedShippingRates->count > 1;


?>

<?php
// @TODO WIP ->NEED TO ALLOW CUSTOMER TO SELECT PREFERRED DELIVERY THEN CONFIRM THAT
// if we have matched shipping rates for customer country
if ($orderMatchedShippingRates->count) {
	$out = '';
	// ------

	$out .= "<div id='order_matched_shipping_rates_wrapper' class='container mx-auto px-6 mb-8'>";
	// display table if we have multiple shipping rates
	if ($isMatchedMultipleShippingRates) {
		// ---------
		$out .= "<h2 class='text-xl font-medium leading-6 text-gray-900 mb-4'>" . __("Matched Shipping Rates") . "</h2>" .
			"<p class='text-lg mb-5'>" . __("Please select one shipping rate.") . "</p>";

		$out .=
			"
    <table id='matched_shipping_rates' class='table-auto w-full text-left whitespace-no-wrap mt-8 mb-12'>
    <thead>
    <tr>
    <th>" . __("Name") . "</th>
    <th>" . __("Delivery Times") . "</th>
    <th>" . __("Fee") . "</th>
    <th>" . __("Select") . "</th>
    </tr>
    </thead>
    <tbody>


    ";

		// ---------------
		// build rows in for matched shipping rates
		foreach ($orderMatchedShippingRates as $matchedShippingRate) {
			$deliveryTimes = sprintf(__('%1$d to %2$d days'), $matchedShippingRate->shippingRateDeliveryTimeMinimumDays, $matchedShippingRate->shippingRateDeliveryTimeMaximumDays);
			// ----------
			// @note: in this case we show the rate itself. It doesn't yet include any taxes (if applicable). The final shipping fee will be shown once a rate is selected
			$shippingFee = $padloper->getValueFormattedAsCurrencyForShop($matchedShippingRate->shippingRate);
			// --------
			$out .=
				"<tr>" .
				// rate name
				"<td>{$matchedShippingRate->shippingRateName}</td>" .
				// delivery times
				"<td>{$deliveryTimes}</td>" .
				// rate / charge / fee
				"<td>{$shippingFee}</td>" .
				// radio selection
				"<td><input type='radio' name='order_selected_shipping_rate' value='{$matchedShippingRate->shippingRateID}'></td>" .
				"<tr>";
		}

		$out .=
			"</tbody>
    </table>";
	} else {
		// ONLY ONE SHIPPING RATE
		// @TODO IN THIS CASE, SHOULD ALREADY HAVE BEEN ADDED AS SELECTED TO THE ORDER!
		$matchedShippingRate = $orderMatchedShippingRates->first();

		// if rate has delivery times
		$deliveryTimesMarkup = '';
		if (!empty($matchedShippingRate->shippingRateDeliveryTimeMinimumDays)) {
			$deliveryTimes = sprintf(__('Delivery between %1$d to %2$d days'), $matchedShippingRate->shippingRateDeliveryTimeMinimumDays, $matchedShippingRate->shippingRateDeliveryTimeMaximumDays);
			$deliveryTimesMarkup = 		"<span>{$deliveryTimes}</span><br>";
		}
		// ------
		// @note: in this case, we show the shipping fee itself as it might include taxes and it is final
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
	}

	// add handling fee if present
	if (!empty($orderHandlingFeeValues->handlingFeeAmount)) {
		$handlingFee = $padloper->getValueFormattedAsCurrencyForShop($orderHandlingFeeValues->handlingFeeAmount);
		// -------
		$out .=
			"<h2 class='text-xl font-medium leading-6 text-gray-900 my-4'>" . __("Handling Fee") . "</h2>" .
			"<span class='block'>" . __("Fee") . ":  {$handlingFee}</span>";
	}

	// add total (handling fee + shipping + taxes included) if present calculated
	// @TODO WIP
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
