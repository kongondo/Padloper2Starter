<?php

namespace ProcessWire;


/**
 * PARTIAL: PRODUCT PRICE AND ADD TO CART
 * @note:
 * - FOR USE WITH single-product-html.php.
 * - CALLED VIA _func.php renderPriceAndAddToCart()
 */

// stock
$stock = $product->padloper_product_stock;
$price = $stock->price;
$priceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($price);
// --------
$selectVariantForPriceText = __("Select variant for price");
// ---------
$isProductWithVariants = $padloper->isProductWithVariants($product);
$xstore = $isProductWithVariants ? "\$store.Padloper2DemoStore" : '';
// -------
// Buy Now (redirect) URL
// @note: adding here so can set programmatically
$redirectURL = "/checkout/";


?>


<div id="padloper_2_demo_product_add_to_cart_wrapper" class="flex mt-4">
    <?php
    $priceMarkup = "";
    // ---------
    if ($isProductWithVariants) {
        // bind product variant price to alpine.js
        $priceMarkup =
            "<div>" .
            "<span class='title-font font-medium text-2xl text-gray-900' x-text='getSelectedProductVariantPriceWithCurrency'></span>" .
            // if a variant is selected and we have a price
            // then hide price variant selection message
            // else show it
            "<small class='block text-indigo-500' :class='{$xstore}.selected_variant_price_with_currency ? `hidden` : ``'>{$selectVariantForPriceText}</small>" .
            "</div>";
    } else {
        // display product's price
        $priceMarkup = "<span class='title-font font-medium text-2xl text-gray-900'>{$priceAsCurrency}</span>";
    }
    // -------------
    echo $priceMarkup;
    ?>
    <form method="post" class="padloper-cart-add-product flex ml-auto" action="<?= $config->urls->root ?>padloper/add/">
        <?php
        $out = "";
        // ---------
        // hidden input for product or variant ID
        if ($isProductWithVariants) {
            // bind product_id to alpine
            $out .= "<input type='hidden' name='product_id' :value='{$xstore}.selected_variant_product_id'/>";
            $bindButtonMarkup = " :disabled='!{$xstore}.selected_variant_price_with_currency'";
        } else {
            // value is product ID
            $out .= "<input type='hidden' name='product_id' value='{$product->id}'/>";
            $bindButtonMarkup = "";
        }
        //------------
        // add CSRF
        $out .= $session->CSRF->renderInput();
        $post = $config->urls->root . "padloper/add/";
        // @note: the hx-target here is not required. this is because we are using oob ( out of bands) by default in the server response  and NOT SWAPPING here, i.e. hx-swap=none! This means the button always stays. It allows us to independently update the markups for quantity, titles number and total amount/price wherever we want, wherever their corresponding markup have been placed.
        //-------
        // @TODO IF USING VARIANTS, NEED TO BIND BUTTON USING ALPINE; OK WITH HTMX?
        $out .= "<button class='text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded' hx-post='{$post}' hx-target='#cart-summary' hx-swap='none'{$bindButtonMarkup}>" .
            __("Add to Cart") .
            "</button>";
        // >>> DEMO 4: REDIRECT CODE <<<
        $out .= "<button data-is-redirect='1' data-redirect-url='{$redirectURL}' class='text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded ml-2' hx-post='{$post}' hx-target='#cart-summary' hx-swap='none'{$bindButtonMarkup}>" .
            __("Buy Now") .
            "</button>";
        // -------------
        echo $out;
        ?>
    </form>
    <button class="rounded-full w-10 h-10 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500 ml-4">
        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z">
            </path>
        </svg>
    </button>
</div>