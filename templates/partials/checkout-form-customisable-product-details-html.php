<?php

namespace ProcessWire;
// -----------------
// bdb($existingProductCustomisationInfo, __METHOD__ . ': $existingProductCustomisationInfo at line #' . __LINE__);
$productCustomisationInputID = "product_customisation_{$productID}";
$label = __("Click to customise");
$label .= " <small class='product_is_customisable text-indigo-500'>" . $productTitle . "</small>";
?>
<!-- CHECKOUT FORM PARTIAL: CUSTOMER PRODUCT CUSTOMISATION DETAILS -->


<div class="col-span-full" x-data="{ open: false }">
  <label for="<?php echo $productCustomisationInputID; ?>" class="block text-sm font-medium text-gray-700 product_customisation_details_label cursor-pointer" x-on:click="open = ! open"><?php echo $label; ?></label>
  <textarea name="<?php echo $productCustomisationInputID; ?>" id="<?php echo $productCustomisationInputID; ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 bg-indigo-100 px-5 py-1 w-full product_customisation_details hide" rows="5" :class="open ? 'open' : ''"><?php echo $existingProductCustomisationInfo; ?></textarea>
</div>