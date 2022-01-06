<?php

namespace ProcessWire;
// @TODO WIP
if (count($downloads)) {
  $out = "";
  // --------
  $out .= "<div  id='order_download_links_wrapper' class='container mx-auto px-6 my-4'>" .
    "<h2 class='text-xl font-medium leading-6 text-gray-900 my-4'>" . __("There are downloads in your order") . "</h2>";
  foreach ($downloads as $href => $title) {
    $out .= "<a class='block' target='_blank' href='{$href}'>{$title}</a>";
  }
  $out .= "</div>";
  // ------
  echo $out;
}
