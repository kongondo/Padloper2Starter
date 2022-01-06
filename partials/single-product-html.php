<?php

namespace ProcessWire;
// -----
// @TODO: ADD TO CART SHOULD ALSO UPDATE SIDE CART!
// description
$description = $product->padloper_description;

// reviews @note: RANDOM VALUES!
// @TODO: NEED TO IMPLEMENT THIS IF YOU NEED IT
$reviews  = rand(5, 500);
$stars  = rand(3, 5);
$starsMax = 5;

// image
$image = "";
if ($product->padloper_images->count()) {
	$firstImage = $product->padloper_images->first();
	// @TODO play with this thumb size as needed!
	$thumb = $product->padloper_images->first()->width(400);
	// --------
	$image = "<img class='lg:w-1/2 w-full h-auto object-cover object-center rounded' src='{$thumb->url}' alt='{$product->title}'>";
}

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
	<div class="container mx-auto px-6">
		<div class="md:flex md:items-center">

			<div class="lg:w-4/5 flex flex-wrap">
				<?php echo $image; ?>
				<div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
					<h2 class="text-sm title-font text-gray-500 tracking-widest uppercase"><?php echo $brandName; ?></h2>
					<h1 class="text-gray-900 text-3xl title-font font-medium mb-1"><?php echo $product->title; ?></h1>
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
					<div class="flex mt-6 items-center pb-5 border-b-2 border-gray-100 mb-5">
						<div class="flex">
							<span class="mr-3"><?php echo __("Colour"); ?></span>
							<button class="border-2 border-gray-300 rounded-full w-6 h-6 focus:outline-none"></button>
							<button class="border-2 border-gray-300 ml-1 bg-gray-700 rounded-full w-6 h-6 focus:outline-none"></button>
							<button class="border-2 border-gray-300 ml-1 bg-indigo-500 rounded-full w-6 h-6 focus:outline-none"></button>
						</div>
						<div class="flex ml-6 items-center">
							<span class="mr-3"><?php echo __("Size"); ?></span>
							<div class="relative">
								<select class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base pl-3 pr-10">
									<?php
									$sizes = ['SM', 'M', 'L', 'XL'];
									$out = "";
									foreach ($sizes as $size) {
										$out .= "  <option>{$size}</option>";
									}
									echo $out;
									?>

								</select>
								<span class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none flex items-center justify-center">
									<svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4" viewBox="0 0 24 24">
										<path d="M6 9l6 6 6-6"></path>
									</svg>
								</span>
							</div>
						</div>
					</div>
					<div class="flex">
						<span class="title-font font-medium text-2xl text-gray-900"><?php echo $priceAsCurrency; ?></span>
						<form method="post" class="padloper-cart-add-product flex ml-auto" action="<?= $config->urls->root ?>padloper/add/">
							<?php
							// @TODO: ADD TO CART SHOULD ALSO UPDATE SIDE CART!
							$out = "";
							// ---------
							$out .= "<input type='hidden' name='product_id' value='{$product->id}'/>";
							// add CSRF
							$out .= $session->CSRF->renderInput();
							$post = $config->urls->root . "padloper/add/";
							// @note: the hx-target here is not required. this is because we are using oob ( out of bands) by default in the server response  and NOT SWAPPING here, i.e. hx-swap=none! This means the button always stays. It allows us to independently update the markups for quantity, titles number and total amount/price wherever we want, wherever their corresponding markup have been placed.
							$out .= "<button class='text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded' hx-post='{$post}' hx-target='#cart-summary' hx-swap='none'>" .
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