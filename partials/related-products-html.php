<?php

namespace ProcessWire;
// -----

// ---------------
// @todo handle not found related products!

?>

<!-- MORE/RELATED PRODUCTS -->
<div id='related_products_wrapper' class="mt-16">
	<h3 class="text-gray-600 text-2xl font-medium"><?php echo __("Related Products"); ?></h3>
	<div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
		<!-- related/more products grid items -->
		<?php
		$out = "";
		if (!is_null($relatedProducts)) {
			foreach ($relatedProducts as $product) {
				// stock
				$stock = $product->padloper_product_stock;
				// @todo: get formatted price from utilities!
				$price = $stock->price;
				// image
				// --------
				$thumbImage = "";
				if ($product->padloper_images->count()) {
					$imageThumb = $product->padloper_images->first()->width(400);
					$thumbImage = "<img class='hover:grow hover:shadow-lg' src='{$imageThumb->url}'>";
				}
				// ---------
				// product url
				// @note: products do not have own guest viewable pages
				// we build a single product's view off urlSegment1 at /products/name-of-product/
				// @note: can be adapted to instead use /products/1234/, where 1234 is the ID of the product
				// @note: $beautify=true
				$productName = $sanitizer->pageName($product->title, $beautify = true);
				$productURL = "/products/{$productName}/";
				$post = $config->urls->root . "padloper/add/";
				// --------
				$out .= "
					<form method='post' class='padloper-cart-add-product action='{$config->urls->root}padloper/add/'>" .
					// input: product ID
					"<input type='hidden' name='product_id' value='{$product->id}'/>" .
					// input: CSRF
					$session->CSRF->renderInput() .
					// related product main markup
					"<div class='w-full max-w-sm mx-auto rounded-md shadow overflow-hidden'>" .
					// related product thumb
					"<div class='flex items-end justify-end h-56 w-full bg-cover' style='background-image: url({$imageThumb->url})'>" .
					// related product add to cart button
					"<button class='p-2 rounded-full bg-indigo-500 text-white mx-5 -mb-4 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600' hx-post='{$post}' hx-target='#cart-summary' hx-swap='none'
												>
												<svg class='h-5 w-5' fill='none' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
												viewBox='0 0 24 24' stroke='currentColor'>
												<path
												d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'>
												</path>
												</svg>
							</button>" .
					"</div>" .
					// related product details
					"<div class='px-5 py-3'>
														<h3 class='text-gray-700 uppercase'><a href='{$productURL}'>{$product->title}</a></h3>
														<span class='text-gray-500 mt-2'>£{$price}</span>
										</div>" .
					// close related product main wrapper and form
					"</div>
						</form>
						";
			}
		} else {
			// no related products found
			$out .=
				"<p>" .
				__("No related products found.") .
				"</p>";
		}
		echo $out;
		?>

	</div>
</div>