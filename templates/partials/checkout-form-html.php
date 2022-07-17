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
				<!-- <div class="md:grid md:grid-cols-12 md:gap-6"> -->
				<!-- CUSTOMER DETAILS + ORDER SUMMARY -->
				<?php
				$out = "";
				if (!empty($cartItems)) {
					$out .= "<form id='padloper_order_customer_form' class='custom md:grid md:grid-cols-12 md:gap-6' method='post' action='./'>" .
						// render checkout form
						// echo renderCheckoutFormCustomerDetails($formErrors, $previousValues);
						"<div id='checkout_form_customer_details_wrapper' class='mt-5 md:mt-0 md:col-span-7'>" .
						renderCheckoutFormCustomerDetails($formErrors, $previousValues) .
						"</div>" .
						// render order summary
						// @todo: can make configurable if to show
						// echo renderCheckoutFormOrderSummary($cartItems);
						"<div id='checkout_form_order_summary_wrapper' class='md:col-span-5 md:order-last'>" .
						renderCheckoutFormOrderSummary($cartItems) .
						"</div>" .
						"</form>";
				} else {
					// cart is empty
					$out .= "<div class='col-span-full px-4 py-5 bg-white sm:p-6'>" .
						"<p>" .  __("Your cart is empty.") . "</p>" .
						"</div>";
					// echo $out;
				}
				// echo output
				echo $out;

				?>
				<!-- </div> -->
			</div>

		</div>
	</div>
</section>