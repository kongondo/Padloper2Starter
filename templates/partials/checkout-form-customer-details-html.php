<?php

namespace ProcessWire;
// -----------------
// bd($formErrors, __METHOD__ . ': $formErrors at line #' . __LINE__);
// bd($previousValues, __METHOD__ . ': $previousValues at line #' . __LINE__);
// bd($shippingCountries, __METHOD__ . ': $shippingCountries at line #' . __LINE__);



// @TODO: HANDLE FORM ERRORS

// @TODO: ALSO RETURN/SHOW PREVIOUS VALUES!

function getFormInputError($formInputName, $formErrors) {
    $errorMarkup = "";
    if (in_array($formInputName, $formErrors)) {
        $errorMarkup = "<small class='padloper_form_input_error text-red-300'>" .
            __("Missing or invalid required value.") .
            "</small>";
    }
    return $errorMarkup;
}

// @TODO: ONLY IF WE HAVE ERRORS!
// @note: we only return values for validated inputs @todo ok?
function getFormPreviousValues($formInputName, $formErrors, $previousValues) {
    if (empty($formErrors) || empty($previousValues)) return;
    // ---------
    // @note: $previousValues will be WireInputData from $input->post of values submitted with the form
    // --------
    $previousValue = '';
    if (!in_array($formInputName, $formErrors)) {
        // if form input was 'valid', we show it again
        $previousValue = $previousValues->get($formInputName);
    }
    bd($previousValue, __METHOD__ . ': $previousValue at line #' . __LINE__);
    return $previousValue;
}

// @TODO: FORM BELOW COULD DO WITH SOME REFACTORING! E.G. THE GETTING PREVIOUS VALUES BIT!
?>
<!-- CHECKOUT FORM PARTIAL: CUSTOMER DETAILS -->
<div id='checkout_form_customer_details_wrapper' class="mt-5 md:mt-0 md:col-span-7">
    <form id="padloper_order_customer_form" class="custom" method="post" action="./">
        <?php echo $session->CSRF->renderInput(); ?>
        <div class="overflow-hidden">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                    <!-- FIRST NAME -->
                    <div class="col-span-6 md:col-span-3">
                        <label for="firstName" class="block text-sm font-medium text-gray-700"><?php echo __("First Name"); ?></label>
                        <input type="text" name="firstName" id="firstName" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('firstName', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('firstName', $formErrors); ?>
                    </div>
                    <!-- LAST NAME -->
                    <div class="col-span-6 md:col-span-3">
                        <label for="lastName" class="block text-sm font-medium text-gray-700"><?php echo __("Last Name"); ?></label>
                        <input type="text" name="lastName" id="lastName" autocomplete="family-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('lastName', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('lastName', $formErrors); ?>
                    </div>
                    <!-- EMAIL -->
                    <div class=" col-span-6">
                        <label for="email" class="block text-sm font-medium text-gray-700"><?php echo __("Email Address"); ?></label>
                        <input type="text" name="email" id="email" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('email', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('email', $formErrors); ?>
                    </div>

                    <!-- ADDRESS LINE 1 -->
                    <div class=" col-span-6">
                        <label for="shippingAddressLineOne" class="block text-sm font-medium text-gray-700"><?php echo __("Address"); ?></label>
                        <input type="text" name="shippingAddressLineOne" id="shippingAddressLineOne" autocomplete="street-address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('shippingAddressLineOne', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('shippingAddressLineOne', $formErrors); ?>
                    </div>
                    <!-- ADDRESS CONTINUED -->
                    <div class=" col-span-6">
                        <label for="shippingAddressLineTwo" class="block text-sm font-medium text-gray-700"><?php echo __("Address (continued)"); ?></label>
                        <input type="text" name="shippingAddressLineTwo" id="shippingAddressLineTwo" autocomplete="street-address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('shippingAddressLineTwo', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('shippingAddressLineTwo', $formErrors); ?>
                    </div>
                    <!-- CITY / TOWN -->
                    <div class=" col-span-6 md:col-span-3">
                        <label for="shippingAddressCity" class="block text-sm font-medium text-gray-700"><?php echo __("City / Town"); ?></label>
                        <input type="text" name="shippingAddressCity" id="shippingAddressCity" autocomplete="address-level2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('shippingAddressCity', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('shippingAddressCity', $formErrors); ?>
                    </div>

                    <!-- POSTCODE / ZIP -->
                    <div class=" col-span-6 md:col-span-3">
                        <label for="shippingAddressPostalCode" class="block text-sm font-medium text-gray-700"><?php echo __("Postcode / Zip"); ?></label>
                        <input type="text" name="shippingAddressPostalCode" id="shippingAddressPostalCode" autocomplete="postal-code" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('shippingAddressPostalCode', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('shippingAddressPostalCode', $formErrors); ?>
                    </div>
                    <!-- COUNTRY -->
                    <div class=" col-span-6 md:col-span-3">
                        <label for="shippingAddressCountry" class="block text-sm font-medium text-gray-700"><?php echo __("Country"); ?></label>
                        <select id="shippingAddressCountry" name="shippingAddressCountry" autocomplete="country-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-indigo-100">
                            <option></option>
                            <!-- @todo: get from utilities + dynamic for shipping countries -->
                            <?php
                            $out = "";
                            // in case form failed validation, we need the previous country value to be selected
                            $previousValue = getFormPreviousValues('shippingAddressCountry', $formErrors, $previousValues);
                            // bd($previousValue, __METHOD__ . ': $previousValue - PREVIOUS COUNTRY VALUE at line #' . __LINE__);
                            // ------------
                            // @TODO check needed here if no shippingCountries found!
                            foreach ($shippingCountries as $shippingCountry) {
                                // @note: SINCE ALL COUNTRIES IN ZONES HAVE IDS (page ID), we use IDs to identify them
                                // $value = $shippingCountry['code'];
                                $value = $shippingCountry['id'];
                                $label = $shippingCountry['name'];
                                // $selected = $value == $previousValue ? " selected='selected'" : '';
                                $selected = $value == $previousValue ? " selected" : '';
                                // bd($selected, __METHOD__ . ': $selected - IS COUNTRY selected? at line #' . __LINE__);
                                // ------------
                                $out .= "<option value='{$value}'{$selected}>$label</option>";
                            }
                            echo $out;
                            ?>
                        </select>
                        <?php echo getFormInputError('shippingAddressCountry', $formErrors); ?>
                    </div>
                    <!-- STATE / PROVINCE -->
                    <div class="col-span-6 md:col-span-3">
                        <label for="shippingAddressRegion" class="block text-sm font-medium text-gray-700"><?php echo __("State / Province"); ?></label>
                        <input type="text" name="shippingAddressRegion" id="shippingAddressRegion" autocomplete="address-level1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1" value="<?php echo getFormPreviousValues('shippingAddressRegion', $formErrors, $previousValues); ?>">
                        <?php echo getFormInputError('shippingAddressRegion', $formErrors); ?>
                    </div>

                </div>
                <!-- @TODO INSERT RADIOS FOR SHIPPING (?) NO: HANDLED VIA SHIPPING ZONES SO WILL NEED TO BE IN CONFIRMATION? -->
                <div class="mt-4 space-y-4">
                    <?php

                    /** @var array $paymentProviders */
                    // get all active payment providers/gateways for this shop
                    $paymentProviders = $padloper->getActivePaymentProviders();

                    $out = "";
                    // in case form failed validation, we need the previous payment value to be checked
                    $previousValue = (int) getFormPreviousValues('padloper_order_payment_id', $formErrors, $previousValues);
                    // bd($previousValue, __METHOD__ . ': $previousValue at line #' . __LINE__);
                    // ---------
                    foreach ($paymentProviders as $paymentGateway) {
                        // @TODO: VALUE IS $id (OF THE PaymentProvider page but might change)
                        $id = $paymentGateway['id'];
                        // $checked = $value == $previousValue ? " checked='checked'" : '';
                        $checked = $id == $previousValue ? " checked" : '';
                        // -----
                        $out .=
                            "<div class='flex items-center'>
						<input id='padloper_payment_{$id}' name='padloper_order_payment_id' type='radio' value='{$id}'$checked class='focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300' required='required'>
						<label for='padloper_payment_{$id}' class='required ml-3 block text-sm font-medium text-gray-700'>" .
                            $paymentGateway['title'] .
                            "</label>
					</div>";
                    }
                    echo $out;

                    ?>
                    <!-- <div class="flex items-center">
						<input id="padloper_payment_paypal" name="pad_paymentmodule" type="radio" value="PadloperPaymentPayPal" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
						<label for="padloper_payment_paypal" class="required ml-3 block text-sm font-medium text-gray-700" required="required">
							<?php echo __("PayPal"); ?>
						</label>
					</div> -->
                    <?php echo getFormInputError('padloper_order_payment_id', $formErrors); ?>
                </div>
            </div>


            <div>
                <button name='customerForm' class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" value="1" type="submit"><?php echo __("Proceed
                    to Confirmation"); ?></button>
            </div>
        </div>
    </form>
</div>