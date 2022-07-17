<?php

namespace ProcessWire;


/**
 * PARTIAL: PRODUCT RATING AND REVIEWS
 * @note:
 * - FOR USE WITH single-product-html.php.
 * - CALLED VIA _func.php renderProductRatingAndReviews()
 */



// rating and reviews
// @todo: need to implement this if you need it
$reviewsCount  = rand(5, 500);
$stars  = rand(3, 5);
$starsMax = 5;


?>


<div id="padloper_2_demo_product_rating_and_reviews_wrapper" class="flex mb-4">
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
        <span class="text-gray-600 ml-3"><?php printf(__("%d Reviews."), $reviewsCount); ?></span>
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