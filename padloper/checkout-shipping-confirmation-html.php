<?php

namespace ProcessWire;

/*
 * Template for customer to confirm shipping/delivery.
 *
 * This is used in case more than one shipping rate has been matched.
 * For instance, a free slower delivery and an express, faster, paid delivery/shipping.
 *
 *
 * Want to customize this template? Please do not edit directly!
 *
 * Just copy this file into /site/templates/padloper/checkout-shipping-confirmation-html.php to modify
 *
 *
 *
 *
 *
 **/


// -------------

$radiosOut = "    <div class='mt-4 space-y-4'>";

foreach ($orderMatchedShippingRates as $matchedShippingRate) {
    // ----------
    // @note: in this case we show the rate itself. It doesn't yet include any taxes (if applicable). The final shipping fee will be shown once a rate is selected
    $shippingFee = $padloper->getValueFormattedAsCurrencyForShop($matchedShippingRate->shippingRate);
    // --------
    $shippingRateID = $matchedShippingRate->shippingRateID;
    $radiosOut .=
        "<div class='flex items-center'>" .
        "<input type='radio' name='order_selected_shipping_rate' value='{$shippingRateID}' id='order_selected_shipping_rate_{$shippingRateID}' class='focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300' required>" .
        "<label for='order_selected_shipping_rate_{$shippingRateID}' class='required ml-3 block text-sm font-medium text-gray-700'>{$matchedShippingRate->shippingRateName} ({$shippingFee})</label>" .
        "</div>";
}
$radiosOut .= "</div>";

// @TODO WHERE TO POST THIS FORM? i think padloper/shipping??

?>

<div class="px-4 py-5 bg-white sm:p-6">
    <div class="flex w-full mb-4">
        <p>
            <?php
            // SHIPPING SELECTION INFO
            echo __("Please select your preferred shipping.");
            ?>
        </p>
    </div>
    <form method="post" class="padloper-checkout-shipping-confirmation" action="./">
        <?php
        // RADIO INPUTS
        echo $radiosOut;
        ?>
        <button type='submit' name='shippingConfirmationForm' value='1' class='text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded mt-6'><?php echo  __("Confirm Shipping"); ?></button>
    </form>
</div>