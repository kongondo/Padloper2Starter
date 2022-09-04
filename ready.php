<?php

namespace ProcessWire;

# Padloper 2 Starter Site - DEMO 4

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

# HOOK to check submitted customer order checkout form for VAT information errors
// e.g. validation of VAT number for customer
$this->addHookAfter('PadloperProcessOrder::checkCustomOrderCustomerFormForErrors', null, 'customCheckCustomOrderCustomerFormForErrors');

# HOOK to check if to apply/exempt EU Digital Goods Tax
$this->addHookAfter('PadloperUtilities::isChargeEUDigitalGoodsTax', null, 'customIsChargeEUDigitalGoodsTax');



# ~~~~~~~~~~ HOOKS FUNCTIONS ~~~~~~~~~~

/**
 * Hook that checks submitted checkout form for VAT information errors.
 *
 * Specifically for customers who state that they are business customers with valid VAT numbers.
 *
 * @param HookEvent $event The Hook event object.
 * @return void
 */
function customCheckCustomOrderCustomerFormForErrors(HookEvent $event) {

	// @note this method has no arguments
	// Retrieve array of all arguments
	// $arguments = $event->arguments();

	$padloper = wire('padloper');
	$post = wire('input')->post;
	$sanitizer = wire('sanitizer');

	// BUSINESS CUSTOMER
	if (!empty((int)$post->is_business_customer)) {
		// customer stated that they are a business customer with a valid VAT number
		$isInvalidVATNumber = false;
		// ----
		// if business customer but VAT number empty: error
		$customerVATNo = $sanitizer->purify($post->customer_vat_number);
		$customerVATNo = preg_replace('/\s+/', '', $customerVATNo);
		if (empty($customerVATNo)) {
			$isInvalidVATNumber = true;
		} else {
			/** @var int $customerCountryID */
			$customerCountryID = (int) $post->shippingAddressCountry;
			/** @var string $customerCountry */
			$customerCountry = getCustomerCountryByID($customerCountryID);
			/** @var array $matchedCustomerCountry */
			$matchedCustomerCountry = $padloper->padloperUtilities->getCountryDetails($customerCountry);
			$customerCountryCode = null;
			if (!empty($matchedCustomerCountry['id'])) {
				$customerCountryCode = $matchedCustomerCountry['id'];
			}

			// check if we got a country code
			if (empty($customerCountryCode)) {
				// no country code match for some reason
				$isInvalidVATNumber = true;
			} else {
				// if business customer but VAT number invalid: error
				$isInvalidVATNumber = empty(isValidVATNumber($customerCountryCode, $customerVATNo));
			}
		}

		#############
		// SET return value
		/** @var array $value */
		$value = $event->return;

		if ($isInvalidVATNumber) {
			// set form error input
			// @note: this will cause checkout to return the form to the customer to correct errors
			$value[] = 'customer_vat_number';
		}
		// set the modified value back to the return value
		$event->return = $value;
	}
}

/**
 * Hook that checks if to apply/exempt EU Digital Goods Tax.
 *
 * Specifically for customers who state that they are business customers with valid VAT numbers.
 * Plus customers whose location country is same as vendors.
 *
 * @param HookEvent $event The Hook event object.
 * @return void
 */
function customIsChargeEUDigitalGoodsTax(HookEvent $event) {

	/* FIRST CHECK GENERAL APPLICABILITY
	- we have an order session
	- customer in EU
	- shop charges EU customers digital tax
	- product is digital

	*/
	$session = wire('session');
	if (empty($session->get('orderId'))) {
		// no order session; return early
		return;
	}

	// get the event object to give us access to PadloperUtility methods
	$hookObject = $event->object;

	// get product settings for this line item
	$lineItemProductSettings = $hookObject->getOrderLineItemProductSettings();

	// ----------
	// CHECK: is A DIGITAL PRODUCT?
	// although checked earlier in PadloperUtilities, we just check again
	if (empty($lineItemProductSettings['data']) || $lineItemProductSettings['data'] !== 'digital') {
		// INVALID: NOT A DIGITAL PRODUCT
		return;
	}

	// ----------
	// CHECK: shop's policy is to charge EU customers EU digital goods vat taxes?
	if (empty($hookObject->isShopChargingEUDigitalGoodsVATTaxes())) {
		// INVALID: NOT CHARGING EU CUSTOMER DIGITAL TAX
		return;
	}

	// ----------
	// CHECK: is customer in the EU?
	if (empty($hookObject->isOrderCustomerShippingAddressInTheEU())) {
		// INVALID: CUSTOMER NOT IN THE EU
		return;
	}


	// +++++++++++++++++++++
	# ***** GOOD TO GO *****

	$post = wire('input')->post;
	$sanitizer = wire('sanitizer');
	// ------
	// @NOTE: HERE WE MIGHT NOT HAVE THIS! SO, USE POST INSTEAD!
	// $currentOrderCustomer = $padloper->getOrderCustomer();
	// bd($currentOrderCustomer, __METHOD__ . ': $currentOrderCustomer - at line #' . __LINE__);

	// @note ->  we determine this programmatically
	$customerVATNo = $sanitizer->purify($post->customer_vat_number);
	$customerVATNo = preg_replace('/\s+/', '', $customerVATNo);
	// ---------
	/** @var int $customerCountryID */
	$customerCountryID = (int) $post->shippingAddressCountry;
	/** @var string $customerCountry */
	$customerCountry = getCustomerCountryByID($customerCountryID);
	/** @var array $matchedCustomerCountry */
	$matchedCustomerCountry = $hookObject->getCountryDetails($customerCountry);
	// -----------
	$newValue = checkIfChargeEUDigitalTax($customerVATNo, $matchedCustomerCountry);

	// SET return value
	/** @var bool $value */
	$value = $event->return;
	$value = $newValue;

	// set the modified value back to the return value
	$event->return = $value;
}

/**
 * Checks if to EU Digital Tax is applicable on line item.
 *
 * @param string $customerVATNo VAT Number to validate.
 * @param array $matchedCustomerCountry Customer country details.
 * @return bool $isChargeEUDigitalGoodsTax Whether to charge digital tax.
 */
function checkIfChargeEUDigitalTax($customerVATNo, $matchedCustomerCountry) {

	$padloper = wire('padloper');
	$isChargeEUDigitalGoodsTax = false;

	// @TODO MAYBE COULD ALSO CHECK EARLIER IF VATNUMBER IS EMPTY -> although affected by other conditions such as 'same country of origin'

	$customerCountryCode = null;
	if (!empty($matchedCustomerCountry['id'])) {
		// we have a customer country code
		$customerCountryCode = $matchedCustomerCountry['id'];
	}

	// get shop settings to grab vendor country code
	$shopGeneralSettings = $padloper->getShopGeneralSettings();
	// -----
	$shopCountryCode = !empty($shopGeneralSettings['country']) ? $shopGeneralSettings['country'] : null;

	$isVendorAndCustomerInSameCountry = false;
	if (!empty($shopCountryCode) && !empty($customerCountryCode)) {
		// we have both vendor and customer country codes
		// let's compare them
		$isVendorAndCustomerInSameCountry = ucwords($shopCountryCode) === ucwords($customerCountryCode);
	}
	// VENDOR AND CUSTOMER **NOT** IN SAME COUNTRY
	// check validity of VAT number
	if (empty($isVendorAndCustomerInSameCountry)) {
		$isChargeEUDigitalGoodsTax = true;
		// ** ONLY FOR BUSINESS CUSTOMERS **
		$post = wire('input')->post;
		// BUSINESS CUSTOMER
		if (!empty((int)$post->is_business_customer)) {
			// customer stated that they are a business customer
			// ------
			// CHECK IF VAT NUMBER IS VALID
			$isValidVATNumber = isValidVATNumber($customerCountryCode, $customerVATNo);
			if (!empty($isValidVATNumber)) {
				// valid VAT Number
				// ---------------
				$isChargeEUDigitalGoodsTax = false;
			}
		}
	}
	// ------------
	return $isChargeEUDigitalGoodsTax;
}

/**
 * Check if a VAT number is valid.
 *
 * Uses simple PHP SOAP client against EU wsdl service.
 *
 * @param string $countryCode Country to validate VAT Number against.
 * @param string $vatNumber VAT Number to validate.
 * @return boolean $isValidVATNumber Whether supplied VAT number is valid.
 */
function isValidVATNumber($countryCode, $vatNumber) {
	// ++++++++++
	# @note: we don't remove country-specific prefixes and such!
	# @todo: for production, developer needs to implement robustness
	// ------------
	$isValidVATNumber = false;
	if (!empty($vatNumber)) {
		// only check if VAT number is not empty
		$wsdl = "http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";
		$client = new \SoapClient($wsdl);
		// -----
		/** @var stdClass $vatDetails */
		$vatDetails = $client->checkVat(array(
			'countryCode' => $countryCode,
			'vatNumber' => $vatNumber
		));
		$isValidVATNumber = !empty($vatDetails->valid);
	}

	return $isValidVATNumber;
}

/**
 * Get customer country using country ID.
 *
 * @param int $customerCountryID Country page ID.
 * @return string|Null $customerCountry Customer country name if found, else Null
 */
function getCustomerCountryByID($customerCountryID) {
	$padloper = wire('padloper');
	$customerCountry = $padloper->getRaw("id={$customerCountryID},template=country", 'title');
	return $customerCountry;
}
