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

# ~~~~~~~~~~ CALL HOOKS ~~~~~~~~~~

# HOOK to amend the unit weight of a product variant
$this->addHookAfter('PadloperUtilities::getProductWeight', null, 'customProductWeight');

# HOOK to process custom details for a product customisation by a customer during checkout
// @note: the method PadloperProcessOrder::orderSaved() does not do anything; it is just for hooking so can hook before or after
$this->addHookBefore('PadloperProcessOrder::orderSaved', null, 'processOrderProductsCustomisation');

# HOOK to amend single order view dashboard to show customer requested product customisation for a line item
$this->addHookAfter('PadloperProcessRenderOrders::getSingleViewTableRow', null, 'customOrderLineItemTableRow');



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

/**
 * Hook that processes, cleans and saves product customisation details for a line item.
 *
 * @param HookEvent $event
 * @return void
 */
function processOrderProductsCustomisation(HookEvent $event) {
  // we need the input to get the product customisation details
  // @note: in this example, we assume only a single customisation per line item; i.e. line item quantity not taken into consideration
  $input = $event->input;

  // Retrieve argument by name
  /** @var PageArray $orderLineItemsPages */
  $orderLineItemsPages = $event->arguments('orderLineItemsPages');
  /** @var WireArray $orderLineItems */
  $orderLineItems =  $event->arguments('orderLineItems');

  ##############
  $padloper = wire('padloper');
  # LOOP THROUGH LINE ITEMS TO SEE WHICH ARE CUSTOMISABLE and if customer sent custom instructions
  // get product IDs selector in order pages

  $productIDsSelector = $orderLineItems->implode('|', 'productID');
  // GET IDs of customisable products in these order line items
  /** @var array $productIDsAllowCustomisation */
  $productIDsAllowCustomisation = $padloper->findRaw("id={$productIDsSelector},product_is_customisable!=''", 'id');
  // @note: id -> order line item page ID; productID -> the id of the product this line item represents
  // @note: there are different approaches here; can loop the line items OR the line items pages OR the product IDs that allow customisation OR the line items values with order line item page ID & product ID
  foreach ($productIDsAllowCustomisation as $productID) {
    // get the order line item page that corresponds to this product ID
    $orderLineItemPage = $orderLineItemsPages->get("padloper_order_line_item.productID={$productID}");
    if (!empty($orderLineItemPage)) {
      // grab and sanitize the custom info sent for this line item's product
      $customisationInfo = $input->post("product_customisation_{$productID}", 'textarea');
      if (!empty($customisationInfo)) {
        // set and save the 'product_customise_details' field
        $orderLineItemPage->setAndSave('product_customise_details', $customisationInfo);
      }
    }
  }
}

/**
 * Hook that amends the markup of table rows in the table that displays line items.
 *
 * This is in a single order view dashboard that displays line items in a table.
 *
 * @param HookEvent $event
 * @return void
 */
function customOrderLineItemTableRow(HookEvent $event) {
  // Retrieve argument by name
  $page = $event->arguments('page');
  // ---------
  // if no custom info, nothing to do
  $customInfo = $page->product_customise_details;
  if (empty($customInfo)) {
    return;
  }
  /** @var array $value */
  $value = $event->return;
  // modify the title column for this table row
  $title = $value[0];
  // append custom info to title
  $title .= "<div class='mt-3'><em>{$customInfo}</em></div>";
  // title is the first element in the array; change it
  $value[0] = $title;
  // set the modified value back to the return value
  $event->return = $value;
}
