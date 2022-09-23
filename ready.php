<?php

namespace ProcessWire;

# Padloper 2 Starter Site - DEMO 6

if (!defined("PROCESSWIRE")) die();

/**
 * ProcessWire Bootstrap API Ready
 * ===============================
 * This ready.php file is called during ProcessWire bootstrap initialization process.
 * This occurs after the current page has been determined and the API is fully ready
 * to use, but before the current page has started rendering. This file receives a
 * copy of all ProcessWire API variables.
 *
 */

# ~~~~~~~~~~ CALL HOOKS ~~~~~~~~~~

# HOOK to modify initial order customer values at order checkout confirmation
// e.g. to set values for a customer who is already logged in
$this->addHookAfter('PadloperProcessOrder::getOrderCustomer', null, 'customGetOrderCustomer');



# ~~~~~~~~~~ HOOKS FUNCTIONS ~~~~~~~~~~

/**
 * Hook that modifies initial order checkout form values.
 *
 * It does this by modifying the initial order customer properties.
 * This only happens if order page has not yet been saved.
 * Useful for cases you want to pre-populate checkout form for logged in users, for instance.
 *
 * @param HookEvent $event The Hook event object.
 * @return void
 */
function customGetOrderCustomer(HookEvent $event) {
	$padloper = wire('padloper');
	$orderPage = $padloper->getOrderPage();

	if ($orderPage instanceof Page) {
		// we already have an order page, hence assume customer details already created and saved with order
		// leave early
		return;
	}
	// GOOD TO SET INITIAL CUSTOMER VALUES

	# *** @NOTE! YOU WILL NEED TO CODE THE LOGIC TO CHECK IF YOUR CUSTOMER IS LOGGED IN + HOW TO RETRIEVE THEIR CUSTOMER DETAILS! ***

	// get the order customer object to populate it with initial values
	/** @var WireData $value */
	$value = $event->return;
	# @see https://docs.kongondo.com/start/checkout/custom-customer-form.html#supported-form-inputs for supported properties
	# in this example, we set customer email and shipping details.
	# @note: shippingAddressCountryID is a runtime value but we set it here so country field in form can be populated

	$pretendLoggedInCustomerDetails = [
		"firstName" => 'Pretender',
		"middleName" => 'Bling',
		"lastName" => 'Fakeone',
		"email" => 'pretend.customer@email.com',
		"shippingAddressFirstName" => 'Pretender',
		"shippingAddressLastName" => 'Fakeone',
		"shippingAddressLineOne" => '235 Pretentious Street',
		"shippingAddressCity" => 'Los Angeles',
		"shippingAddressRegion" => 'California',
		"shippingAddressCountry" => 'Andorra',
		"shippingAddressPostalCode" => 'S234 BE',
		"billingAddressFirstName" => 'MisterPretend',
		"billingAddressLastName" => 'Fakebiz',
		"billingAddressLineOne" => '456 Mybiz Avenue',
		"billingAddressLineTwo" => 'Suite 13',
		"billingAddressCity" => 'La Massana',
		"billingAddressCountry" => 'Andorra',
		"billingAddressPostalCode" => 'ANM45 999',
		// ------
		// @TODO -> you need to grab this ID programmatically from your shop's countries!
		"shippingAddressCountryID" => 1931 # @NOTE: fake/testing ID!
	];

	// set values to return value (order customer)
	$value->setArray($pretendLoggedInCustomerDetails);
	$event->return = $value;
}
