<?php

namespace ProcessWire;

/**
 * @note:
 * - Template partial for a single product.
 * - Called by renderSingleProduct() in _func.php
 * - renderSingleProduct() sets $product, $numberOfTitles, $totalAmount and $totalQuantity
 */

// get the variants for this product
$productVariants = null;


///////////////////////////////////


// -----




// ------------
$productVariants = '';
$script = "";
// image
// @TODO CHANGE THIS TO CHECK FOR PRODUCT WITH VARIANTS VS ONE WITHOUT
$image = "";
$productFirstImage = null;
// product has images
if ($product->padloper_images->count()) {
  $productFirstImage = $product->padloper_images->first();
  // @TODO play with this thumb size as needed!
  $productFirstImageThumb = $productFirstImage->width(400);
}

// PRODUCT WITH VARIANTS

// prepare markup to init alpine.js
$alpineJSInitMarkup = " x-data='Padloper2DemoData' x-init='initVariants()'";
// --------
$xstore = "\$store.Padloper2DemoStore";
// @note: in this case, we bind image to alpine.js
// on load, main product image is the active image
$image = "<img class='lg:w-1/2 w-full h-auto object-cover object-center rounded' :src='{$xstore}.active_image.image_full_url' :alt='{$xstore}.active_image.alt'>";

$variantsInfo = [];

// @TODO IS IT MORE EFFICIENT THIS WAY? i.e. getting all variants first as findRaw, then getting images separately? we need an object for the images, in order to do thumbs, etc

// all variants in product
/** @var array $variants */
$variantsRaw = getProductVariantsRaw($product);
bd($variantsRaw, __METHOD__ . ': $variantsRaw at line #' . __LINE__);
$variants = getProductVariantsFormattedInfo($variantsRaw);
bd($variants, __METHOD__ . ': $variants at line #' . __LINE__);

// all options in these variants
$allOptions = array_column($variantsRaw, 'options');
bd($allOptions, __METHOD__ . ': $allOptions at line #' . __LINE__);
// get all selectable sizes in variants
$variantsSelectableSizes = getProductSelelectableVariantSizes($allOptions);
bd($variantsSelectableSizes, __METHOD__ . ': $variantsSelectableSizes at line #' . __LINE__);


bd($allOptions, __METHOD__ . ': $allOptions at line #' . __LINE__);

$selectVariantForPriceText = __("Select variant for price");
/** @var PageArray $variantsWithImages */
$variantsWithImages = $padloper->find("template=variant,parent={$product},images.count>0");
bd($variantsWithImages, __METHOD__ . ': $variantsWithImages at line #' . __LINE__);
// if we have variants with images
$variantsForAttributeOptionsSelectionsColour = [];
// first, add the main product image, if any
if (!empty($productFirstImage)) {
  $imageInfo = [
    'variant_id' => 0,
    'image_thumb_url' => $productFirstImageThumb->url,
    'image_full_url' => $productFirstImage->url,
    'alt' => $product->title,
  ];
}
// add to variants images array
$variantsForAttributeOptionsSelectionsColour[] = $imageInfo;
// -------
// process the images for selection using alpine.js
if (!empty($variantsWithImages->count())) {

  // add the variants' images themselves
  foreach ($variantsWithImages as $variant) {
    /** @var Pageimage $image */
    $variantImage = $variant->padloper_images->first();
    $variantThumb = $variantImage->width(400);
    // -------
    // GET THE OPTIONS that make up this variant
    // we will then extract the 'colour' info from them
    /** @var PageArray $options */
    $options = $variant->padloper_product_attributes_options;
    // bd($options, __METHOD__ . ': $options at line #' . __LINE__);
    // COLOUR + SIZE OPTIONS ->
    // @TODO @NOTE: HARDCODED! you need to adjust this for your shop as required!
    // @TODO: you will also need to account for multilingual setups
    // we get the option whose parent name is colour
    # colour option #
    /** @var Page $colourOption */
    $colourOption = $options->get("parent.name=colour");
    // bd($colourOption, __METHOD__ . ': $colourOption at line #' . __LINE__);
    // --------
    $alt = $colourOption->title;
    // --------
    $imageInfo = [
      'variant_id' => $variant->id,
      'image_thumb_url' => $variantThumb->url,
      'image_full_url' => $variantImage->url,
      'alt' => $alt,
      'colour_id' => $colourOption->id,
      'colour_name' => $colourOption->title,
    ];
    // add to variants colours + images array
    $variantsForAttributeOptionsSelectionsColour[] = $imageInfo;
  }
  bd($variantsForAttributeOptionsSelectionsColour, __METHOD__ . ': $variantsForAttributeOptionsSelectionsColour at line #' . __LINE__);

  $imagesInfo = $variantsForAttributeOptionsSelectionsColour;

  // -----
  $variantsForAttributeOptionsSelections = [
    'colour' => $variantsForAttributeOptionsSelectionsColour,
    'size' => $variantsSelectableSizes,
  ];

  $productVariants = renderProductVariants($variantsForAttributeOptionsSelections);


  // ---------
  // POPULATE VARIANTS INFO FOR JavaScript
  // @note: 'images' also used for 'colour' selection!
  $variantsInfo['images'] = $imagesInfo;
  $variantsInfo['sizes'] = $variantsSelectableSizes;
  $variantsInfo['all_variants'] = $variants;
  $variantsInfo['no_colour_selection'] = __("Select a colour");
  $variantsInfo['no_size_selection'] = __("Select a size");
  $variantsInfo['no_variant_selection'] = __("Please select a variant");
  $variantsInfo['no_variant_selection_price'] = $selectVariantForPriceText;
  // -------------
  $script = "<script>PadloperDemoVariants = " . json_encode($variantsInfo) . ';</script>';
}

// description
$description = $product->padloper_description;

// reviews
// @todo: need to implement this if you need it
$reviews  = rand(5, 500);
$stars  = rand(3, 5);
$starsMax = 5;

// stock
$stock = $product->padloper_product_stock;
$price = $stock->price;
$priceAsCurrency = $padloper->getValueFormattedAsCurrencyForShop($price);

// brand
$brandName =  __("No Brand.");
if ($product->padloper_brand) {
  $brandName = $product->padloper_brand->title;
}

# HTMX
// htmx POST
$post = $config->urls->root;
// htmx Attributes
// @note: hx-trigger will be triggered via js by the event 'padloperfetchupdatedcart' which is a result of a single product (direct or related) getting added to the cart.

?>


<div id='padloper_add_single_product' class="my-8" hx-trigger="padloperfetchupdatedcart delay:500ms" hx-post="<?php echo $post; ?>" hx-target="#padloper_side_cart" hx-vals='{"is_single_product_add": 1}'>
  <div class="container mx-auto px-6" <?php echo $alpineJSInitMarkup; ?>>
    <div class="md:flex md:items-center">

      <div class="lg:w-4/5 flex flex-wrap">
        <?php echo $image; ?>
        <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
          <h2 class="text-sm title-font text-gray-500 tracking-widest uppercase"><?php echo $brandName; ?></h2>
          <h1 class="text-gray-900 text-3xl title-font font-medium mb-1"><?php echo $product->title; ?></h1>
          <?php
          // bind and display active/selected variant name if product has variant

          echo "<span class='text-indigo-500 mb-1 block' x-text='getSelectedProductVariantName()'></span>";

          ?>
          <div class="flex mb-4">
            <span class="flex items-center">
              <?php
              $out = "";
              $i = 1;
              while ($i <= $stars) {
                $out .=
                  "<svg fill='currentColor' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='w-4 h-4 text-indigo-500' viewBox='0 0 24 24'>
									<path d='M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z'>
									</path>
									</svg>";
                $i++;
              }
              // if not 5/5
              if ($stars < $starsMax) {
                // EMPTY STAR
                $out .=
                  "<svg fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='w-4 h-4 text-indigo-500' viewBox='0 0 24 24'>
									<path d='M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z'>
									</path>
									</svg>";
              }

              echo $out;
              ?>
              <span class="text-gray-600 ml-3"><?php printf(__("%d Reviews."), $reviews); ?></span>
            </span>
            <span class="flex ml-3 pl-3 py-2 border-l-2 border-gray-200 space-x-2s">
              <a class="text-gray-500">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z">
                  </path>
                </svg>
              </a>
              <a class="text-gray-500">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                  </path>
                </svg>
              </a>
              <a class="text-gray-500">
                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                  <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z">
                  </path>
                </svg>
              </a>
            </span>
          </div>
          <p class="leading-relaxed"><?php echo $description; ?></p>
          <?php echo $productVariants; ?>
          <div class="flex">
            <?php

            // bind product variant price to alpine.js
            $priceMarkup =
              "<div>" .
              "<span class='title-font font-medium text-2xl text-gray-900' x-text='getSelectedProductVariantPriceWithCurrency'></span>" .
              // if a variant is selected and we have a price
              // then hide price variant selection message
              // else show it
              "<small class='block text-indigo-500' :class='{$xstore}.selected_variant_price_with_currency ? `hidden` : ``'>{$selectVariantForPriceText}</small>" .
              "</div>";

            // -------------
            echo $priceMarkup;
            ?>
            <form method="post" class="padloper-cart-add-product flex ml-auto" action="<?= $config->urls->root ?>padloper/add/">
              <?php
              $out = "";
              // ---------
              // hidden input for variant ID
              // bind product_id to alpine
              $out .= "<input type='hidden' name='product_id' :value='{$xstore}.selected_variant_product_id'/>";
              $bindButtonMarkup = " :disabled='!{$xstore}.selected_variant_price_with_currency'";

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
        </div>
      </div>
    </div>
    <!-- MORE/RELATED PRODUCTS -->
    <?php echo renderRelatedProducts($product, "limit=4"); ?>
  </div>

  <?php
  //  @TODO: MOVE TO SIDE-CART? hidden for now!
  $out = "<div id='cart-summary' class='hidden'>" .
    "<span>Products: <span id='numberOfTitles'>{$numberOfTitles}</span></span>" .
    "<span>Quantity: <span id='totalQty'>{$totalQuantity}</span></span>" .
    // @todo: testing out of bands
    "<span>Price: <span id='totalAmount'>{$totalAmount}</span></span>" .
    "</div>";
  echo $out;

  ?>
</div>

<?php
// VARIANT IMAGES FOR ALPINE JS
echo $script;

?>