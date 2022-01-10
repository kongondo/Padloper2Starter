<?php

namespace ProcessWire;

// categories.php (all categories or single if urlSegment 1) template file.

// Primary content
$content = "";

// ----------
// first check if single category view requested
// @note: $beautify=true
$singleCategoryName = $sanitizer->pageName($input->urlSegment1, $beautify = true);
if (!empty($singleCategoryName)) {
	// get the category
	$category = $padloper->get("template=category,name={$singleCategoryName}");
	if (!empty($category->id)) {
		$selector = "categories={$category->id}";
		$productsContent = renderProducts($selector);
		if (empty($productsContent)) {
			// @todo: here and elsewhere, these divs should be in _main.php!
			// no products in this collection
			$content .=
				" <div>
					<p class='px-4'>" .
				sprintf(__('Sorry, we currently do not have any products in %s. Please try other Collections.'), $category->title) .
				"</p>
				</div>";
		} else {
			// products matching collection found
			$content .= $productsContent;
		}
	} else {
		// @todo: 404 instead?
		// throw new Wire404Exception();
		$content .=
			" <div class='container mx-auto px-6'>
			<p class='px-4'>" .
			__("Sorry, we could not find that Collection. Please use search function.") .
			"</p>
		</div>";
	}
	$sectionHeader = __("Shop All Collections");
	$sectionSubHeader = $category->title;
	$sectionURL = "/categories/";
	$isShowSectionBackArrow = true;
	$title = $category->title;
} else {
	$sectionHeader = __("Collections");
	$sectionURL = "";
	// show all (limited, paginaged) categories
	// $content = $page->body;
	// get 50 categories
	// @todo: sort?
	// @TODO: MAYBE CHANGE TO FIND ONLY REFERENCED? @see https://processwire.com/blog/posts/processwire-3.0.95-core-updates/ OWNER SELECTORS
	$selector = "limit=50";
	$content .= renderCategories($selector);
}
