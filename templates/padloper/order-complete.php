<?php

namespace ProcessWire;

$out = "";
// --------

$cartRender = $padloper->cartRender;

$out .= "<div  id='order_complete_thank_you_wrapper' class='container mx-auto px-6 my-4'>" .
  "<p class='text-xl'>" . __("Thank you. Your order is complete.") .  "</p>" .
  "</div>";

// --------------
// ORDER DOWNLOADS
/** @var array $downloads */
if (!empty($downloads)) {
  /** @var TemplateFile $t */
  $t = $cartRender->getPadTemplate("order-downloadlinks.php");
  $t->set("downloads", $downloads);
  $out .= $t->render();
}

// --------------
// ORDER CUSTOMER INFORMATION
$t = $cartRender->getPadTemplate("order-customer-information.php");
/** @var WireData $orderCustomer */
$t->set("orderCustomer", $orderCustomer);
$out .= $t->render();

// --------------
// ORDER META INFORMATION
$t = $cartRender->getPadTemplate("order-meta-information.php");
$t->set("order", $order);
$out .= $t->render();

// @TODO @KONGONGO AMENDMENTS WIP
// --------------
// ORDER LINE ITEMS
$t = $cartRender->getPadTemplate("order-products-table.php");
$t->set("order", $order);
$t->set("orderLineItems", $orderLineItems);
$t->set("orderSubtotal", $orderSubtotal);
$t->set("isOrderGrandTotalComplete", $isOrderGrandTotalComplete);
$t->set("isOrderConfirmed", $isOrderConfirmed);

$out .= $t->render();

// -------
echo $out;
