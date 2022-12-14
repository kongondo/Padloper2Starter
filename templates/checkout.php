<?php

namespace ProcessWire;


// checkout.php template file.

// @DO THE CART IS EMPTY, ETC THING!

$checkout = $padloper->checkout;
// for comparison only. These no longer in use in this format in Padloper 2
// $checkout = $modules->get("PadCheckout"); // This is the old one, use above for most streamlined checkout
// @see notes below: Padloper 2 utilises Shipping zones. Shipping will be automatically calculated based on matched shipping rates in matched shipping zone.
// @todo: delete when done; no longer in use
// $checkout->setShippingModule("ShippingFixed"); // This means that all orders will use this specific

/*
 @note:
 - EXAMPLE OF HOW TO PROCESS A CUSTOM ORDER CUSTOMER FORM
 - Padloper 2 also ships with an inbuilt form similar to Padloper 1
 - The inbuilt form utilises ProcessWire Inputfields
 - Not everyone prefers that due to CSS styling issues
 - Both the inbuilt and the custom form processors will validate the form
 - The inbuilt one will utilise ProcessWire $form->getErrors()
 - IF YOUR FORM USES CUSTOM NAMES FOR ITS INPUT, you will need to indicate this + provide a 'schema' for the name matching
 - @see below for details
 - The custom form processor will need the a schema of the custom form passed to it
 - e.g.:

 	$customFormFieldsExampleArray = [
			[
				// the name of the input of the custom form
				'input_name' => 'email',
				// the input type (for sanitization)
				'type' => 'email',
				// if field/input is required
				'required' => true
			],
			[
				'input_name' => 'firstName',
				'type' => 'text',
				'required' => false // can be left out
            ],

		];

		// @note: the payment class input names do not have aliases. They need to be used as is, i.e.
		'padloper_order_payment_id'
		// @note: shipping is handled using Shipping Zones in Padloper 2. There is no need to select a shipping method
		in a future release, customers will be able to select shipping rate if more than one shipping rate has been matched
		e.g. standard delivery - 7.99; express delivery 13.50, etc.

*/

$customFormFields = [
	// first name
	[
		// the name of the input of the custom form
		'input_name' => 'firstName',
		// the input type (for sanitization)
		// @todo: @note: for selects and checkbox, use the expected value type
		'type' => 'text',
		// if field/input is required
		'required' => true
	],
	// last name
	[
		'input_name' => 'lastName',
		'type' => 'text',
		'required' => true
	],
	// email
	[
		'input_name' => 'email',
		'type' => 'email',
		'required' => true
	],
	// address line one
	[
		'input_name' => 'shippingAddressLineOne',
		'type' => 'text',
		'required' => true
	],
	// address line two
	[
		'input_name' => 'shippingAddressLineTwo',
		'type' => 'text',
	],
	// city/town
	[
		'input_name' => 'shippingAddressCity',
		'type' => 'text',
		'required' => true
	],
	// postcode
	[
		'input_name' => 'shippingAddressPostalCode',
		'type' => 'text',
		'required' => true
	],
	// country
	[
		'input_name' => 'shippingAddressCountry',
		// @note: country ID, hence integer!
		'type' => 'integer',
		'required' => true
	],
	// region/state/province
	[
		'input_name' => 'shippingAddressRegion',
		'type' => 'text'
	],
	// ------------------------

	// SPECIAL (NON-ALIASED)
	// PAYMENT TYPE/CLASS
	// @todo: @note: name might change!
	[
		'input_name' => 'padloper_order_payment_id',
		'type' => 'integer',
		'required' => true
	],

];

// -------------
// Primary content
$content = "";
$formErrors = [];
$previousValues = null;
$sectionHeader = __("Continue Shopping");
$sectionURL = "/products/";
$isShowSectionBackArrow = true;
$isShowCart = false;
$isCustomForm = true;
// if ($user->isSuperuser()) {
// 	$isCustomForm = false;
// }
// $isCustomForm = false;

// @TODO: RETHINK BELOW! CAN'T WE PASS PadloperCheckout::render() a parameter to tell NOT TO RENDER CONTENT? IT CAN THEN CONTINUE TO LISTEN TO CANCEL/SUCCESS/CONFIRMATION/INVOICE and handle those urlSegments. In those cases it does not need any special form. This means that our only 'issue' is renderForm(). If we pass this flag, instead of it will ignore rendering BUT LISTEN TO POST OF BUTTON customerForm. To handle it, again if custom, it will pass to processCustomOrderCustomerForm::render(). Hence, we can still use our custom form, do the equivalent, etc! HOWEVER, IF CUSTOM, THEN HERE, WE LISTEN TO RESPONSE FROM $checkout->render() -> feels a bit dirty since it actually doesn't render anything (or renders a blan!) but also returns WireData error messages. We can continue to use those as usual in formErrors and previousValues
// ----------------
$options = [
	// determines if to output internal form using renderForm() vs dev using custom form
	'is_custom_form' => $isCustomForm,
	// will hold schema for form inputs if custom form will be used
	'custom_form_fields' => $customFormFields,
	// when custom form is used, will it use custom input names or identical names to internal form
	'is_use_custom_form_input_names' => true

];
/** @var WireData $response */
// $response = $checkout->render($options);
// ----------
// @TODO: @NOTE: JUST SHOWING HOW TO USE INBUILT VS CUSTOM FORM
// YOU WOULDN'T NEED THIS CHECK IF YOU KNEW YOU ARE USING A CUSTOM FORM :-)
if (!empty($isCustomForm)) {
	$response = $checkout->render($options);

	// handle errors
	if (!empty($response->errors)) {
		$formErrors = $response->errors;
		$previousValues = $response->previousValues;
		// -----------
		$content .= renderCheckoutForm($formErrors, $previousValues);
	} elseif (!empty($response->isProcessedForm)) {
		// @note: this will be result of renderConfirmation, success, etc when using custom form
		$content .= $response->content;
	} else {
		$content .= renderCheckoutForm($formErrors, $previousValues);
	}
	// @TODO HERE NEED TO CHECK IF CONFIRMATION AND OUTPUT ITS CONTENT
	// ---------
	// $content .= renderCheckoutForm($formErrors, $previousValues);
} else {
	// @note: left here for comparison. In this demo, we use a custom form. Below would utilise the inbuit ProcessWire form
	$content .= $checkout->render();
}
