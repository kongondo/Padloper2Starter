<?php

namespace ProcessWire;

// @NOTE: @see documentation (WIP) for all potentially available Order Customer Details/Information

?>
<div id='order_customer_information_wrapper' class="container mx-auto px-6 my-4">
  <?php
  $out = "";
  // --------

  if (!empty($orderCustomer)) {
    $out .= "<span class='block'>{$orderCustomer->firstName} {$orderCustomer->lastName}</span>";
    # BUILD CUSTOMER DETAILS MARKUP #
    if ($orderCustomer->shippingAddressLineOne) {
      // SHIPPING ADDRESS LINE ONE
      $out .= "<span class='block'>{$orderCustomer->shippingAddressLineOne}</span>";
    }
    if ($orderCustomer->shippingAddressLineTwo) {
      // SHIPPING ADDRESS LINE TWO
      $out .= "<span class='block'>{$orderCustomer->shippingAddressLineTwo}</span>";
    }
    if ($orderCustomer->shippingAddressCity || $orderCustomer->shippingAddressPostalCode) {
      if ($orderCustomer->shippingAddressPostalCode) {
        // POSTALCODE + CITY/TOWN
        $out .= "<span class='block'>{$orderCustomer->shippingAddressPostalCode}</span>" .
          "<span class='block'>{$orderCustomer->shippingAddressCity}</span>";
      }
    }
    // SHIPPING ADDRESS PHONE
    if ($orderCustomer->shippingAddressPhone) {
      $out .= "<span class='block'>{$orderCustomer->shippingAddressPhone}</span>";
    }
  }

  // ------
  echo $out;
  ?>
</div>