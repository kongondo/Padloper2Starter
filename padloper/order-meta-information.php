<?php

namespace ProcessWire; ?>

<div id='order_meta_information_wrapper' class="container mx-auto px-6 my-4">
  <div class="block">
    <span class="mr-1 font-semibold"><?php echo  __("Invoice #") ?>:</span>
    <span><?php echo  $order->id ?></span>
  </div>
  <div class="block">
    <span class="mr-1 font-semibold"><?php echo  __("Date") ?>:</span>
    <span><?php echo  date($padloper->getShopDateFormat(), $order->created) ?></span>
  </div>
</div>