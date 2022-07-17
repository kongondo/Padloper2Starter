<?php

namespace ProcessWire;
// -----------------


?>
<!-- CHECKOUT FORM PARTIAL -->
<section class="text-gray-600 body-font overflow-hidden">
	<div id='checkout_form_wrapper' class="container px-5 py-8 mx-auto">
		<div>
			<h2 class="text-2xl font-medium leading-6 text-gray-900 px-4"><?php echo __("Checkout"); ?></h2>
			<div class="hidden sm:block" aria-hidden="true">
				<div class="py-5">
					<div class="border-t border-gray-200"></div>
				</div>
			</div>
			<div class="mt-10 sm:mt-0">
				<div class="md:grid md:grid-cols-12 md:gap-6">
					<!-- CUSTOMER DETAILS + ORDER SUMMARY -->
					<?php
					if (!empty($cartItems)) {
						// render checkout form
						echo renderCheckoutFormCustomerDetails($formErrors, $previousValues);
						// render order summary
						// @todo: can make configurable if to show
						echo renderCheckoutFormOrderSummary($cartItems);
					} else {
						// cart is empty
						$out = "<div class='col-span-full px-4 py-5 bg-white sm:p-6'>" .
							"<p>" .  __("Your cart is empty.") . "</p>" .
							"</div>";
						echo $out;
					}

					?>
				</div>
			</div>

		</div>
	</div>
</section>