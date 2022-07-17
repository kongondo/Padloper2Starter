<?php

namespace ProcessWire;
// ==================

// @TODO @KONGONDO PORT


$cart = $padloper->cart;
// bdb($pl, '$pl -  at line #' . __LINE__);
// bdb($cart, '$cart -  at line #' . __LINE__);
// bdb($order, '$order -  at line #' . __LINE__);
// ==================
// @TODO @KONGONDO PORT
$cartRender = $padloper->cartRender;
// -------

// ------
// $orderLineItems = $padloper->getOrderLineItems();
// bd($order, __METHOD__ . ': $order at line #' . __LINE__);
// bd($orderLineItems, __METHOD__ . ': $orderLineItems at line #' . __LINE__);
// bd($orderMatchedShippingRates, __METHOD__ . ': $orderMatchedShippingRates at line #' . __LINE__);
// bd($orderHandlingFeeValues, __METHOD__ . ': $orderHandlingFeeValues at line #' . __LINE__);

// db($order, __METHOD__ . ': $order at line #' . __LINE__);
// db($orderLineItems, __METHOD__ . ': $orderLineItems at line #' . __LINE__);
// db($orderCustomer, __METHOD__ . ': $orderCustomer at line #' . __LINE__);

// @kongondo rename here and below
?>
<div class="container mx-auto px-6">
    <h2 class="text-xl font-medium leading-6 text-gray-900 my-4"><?php echo __("Please verify your order"); ?></h2>
</div>


<?php
/** @var TemplateFile $t */
// @TODO: ONLY RENDER IF CUSTOMER INFORMATION WAS ENTERED!
$t = $cartRender->getPadTemplate("order-customer-information.php");
// ==============
// @note
// here we set the property order to the value of $order so that it can be used in the newly created virtual TemplateFile $t which uses the file "order-customer-information.php" AS ITS TEMPLATE FILE
// @note: TemplateFile extends WireData, hence here this is what happens: parent::set($property, $value);
// @note: $order itself was alreay set to this file, as a TemplateFile property in PadloperCheckout.php
// @see: PadloperCheckout::renderConfirmation
/** @var WireData $order */
$t->set("order", $order);
/** @var WireData $orderCustomer */
$t->set("orderCustomer", $orderCustomer);
echo $t->render();
?>



<?php

// if ($order->pad_discount_code) {
//     $code = $sanitizer->selectorValue($order->pad_discount_code);
//     $dc = $pages->get("template=paddiscount, title=$code");
//     if ($dc->id) echo "<p>" . sprintf(__('Discount code %1$s is applied, and your order gets %2$s%% discount.'), $code, $dc->pad_percentage) . "</p>";
// }
/** @var TemplateFile $t */
$t = $cartRender->getPadTemplate("order-products-table.php");
/** @var WireData $order */
$t->set("order", $order);
/** @var WireArray $orderLineItems */
$t->set("orderLineItems", $orderLineItems);
/** @var float $orderSubtotal */
$t->set("orderSubtotal", $orderSubtotal);
/** @var bool $isOrderGrandTotalComplete */
$t->set("isOrderGrandTotalComplete", $isOrderGrandTotalComplete);
/** @var bool $isOrderConfirmed */
$t->set("isOrderConfirmed", $isOrderConfirmed);
echo $t->render();

/** @var TemplateFile $t */
$t = $cartRender->getPadTemplate("order-shipping-information.php");
/** @var WireData $order */
$t->set("order", $order);
/** @var WireArray $orderMatchedShippingRates */
$t->set("orderMatchedShippingRates", $orderMatchedShippingRates);
/** @var WireData $orderHandlingFeeValues */
$t->set("orderHandlingFeeValues", $orderHandlingFeeValues);
/** @var bool $isOrderGrandTotalComplete */
$t->set("isOrderGrandTotalComplete", $isOrderGrandTotalComplete);
/** @var float $orderGrandTotal */
$t->set("orderGrandTotal", $orderGrandTotal);
echo $t->render();
