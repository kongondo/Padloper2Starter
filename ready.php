<?php

namespace ProcessWire;

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

# HOOK to amend
$this->addHookAfter('PadloperUtilities::getProductWeight', null, 'customProductWeight');



# ~~~~~~~~~~ HOOKS FUNCTIONS ~~~~~~~~~~

/**
 * Hook that amends the unit weight of a single product.
 *
 * Specifically the unit weight of a product variant
 *
 * @param HookEvent $event The Hook event object.
 * @return void
 */
function customProductWeight(HookEvent $event) {

  // Retrieve argument by name
  /** @var Page $product */
  $product = $event->arguments('product');

  // 1. FIRST, CHECK IF THIS PRODUCT HAS A CUSTOM WEIGHT FIELD
  if (!$product->hasField('custom_weight')) {
    // main product: does not have 'custom_weight' field
    return;
  }

  // 2. CHECK IF THIS PRODUCT HAS A WEIGHT ENTERED in custom field
  $unitProductWeight = $product->get('custom_weight');
  if (empty($unitProductWeight)) {
    // a variant product without a weight specified: fall back to main product weight
    return;
  }

  // 3. SET VARIANT PRODUCT WEIGHT as return value
  /** @var float $return */
  $value = $event->return;
  $value = (float) $unitProductWeight;
  // set the modified value back to the return value
  // PadloperUtilities::getOrderWeight will use this new value to calculate this variants weight
  // it will take care of computing its total weight based on product quantity
  $event->return = $value;
}
