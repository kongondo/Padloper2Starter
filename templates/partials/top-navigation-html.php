<?php

namespace ProcessWire;
// ==================

$cart = $padloper->cart;
// $cartQuantity = $cart->getQuantity();
$cartUniqueTitles = $cart->getNumberOfTitles();


/** @var WireData $shopSettings */
$shopSettings = $padloper->getShopGeneralSettings();
$shopName = $shopSettings->shop_name;
if (empty($shopName)) {
	$shopSettings = __("Nameless Shop");
}

function isActiveMenuItem($url) {
	$page = wire('page');
	$pathArray = explode("/", $page->path);
	$urlArray = explode("/", $url);
	// ---
	$filteredIntersect = array_filter(array_intersect($pathArray, $urlArray));
	$isActiveMenuItem = false;
	if (!empty($filteredIntersect)) {
		$isActiveMenuItem = true;
	} elseif ($url === $page->path) {
		$isActiveMenuItem = true;
	}
	// ---------
	return $isActiveMenuItem;
}
?>

<label for="menu-toggle" class="cursor-pointer md:hidden block">
	<svg class="fill-current text-gray-900" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
		<title>menu</title>
		<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
	</svg>
</label>
<input class="hidden" type="checkbox" id="menu-toggle" />

<div class="hidden md:flex md:items-center md:w-auto w-full order-3 md:order-1" id="menu">
	<nav>
		<ul class="md:flex items-center justify-between text-base text-gray-700 pt-4 md:pt-0">

			<?php

			$navigationItems = [
				[
					'label' => __("Shop"),
					'url' =>  '/',
				],
				[
					'label' => __("Collections"),
					'url' =>  '/categories/'
				],
				[
					'label' => __("Best Sellers"),
					'url' =>  '#'
				],
				[
					'label' => __("About"),
					'url' =>  '/about/'
				],
			];

			$out = "";
			foreach ($navigationItems as $navigationItem) {

				$label = $navigationItem['label'];
				$url = $navigationItem['url'];
				// $activeClass = isActiveMenuItem($url) ? ' text-indigo-500' : '';
				$activeClass = isActiveMenuItem($url) ? ' font-semibold' : '';
				$out .= "<li><a class='inline-block no-underline hover:text-black hover:underline py-2 px-4{$activeClass}' href='{$url}'>{$label}</a></li>";
			}
			// ---------
			echo $out;

			?>

		</ul>
	</nav>
</div>

<div class="order-1 md:order-2">
	<a class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="/">
		<svg class="fill-current text-gray-800 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<path d="M5,22h14c1.103,0,2-0.897,2-2V9c0-0.553-0.447-1-1-1h-3V7c0-2.757-2.243-5-5-5S7,4.243,7,7v1H4C3.447,8,3,8.447,3,9v11 C3,21.103,3.897,22,5,22z M9,7c0-1.654,1.346-3,3-3s3,1.346,3,3v1H9V7z M5,10h2v2h2v-2h6v2h2v-2h2l0.002,10H5V10z" />
		</svg>
		<span class="uppercase"><?php echo $shopName; ?></span>
	</a>
</div>

<div class="order-2 md:order-3 flex items-center" id="nav-content">
	<a class="pl-3 inline-block no-underline hover:text-black" href="#" @click="setIsCartOpen">
		<svg class="fill-current hover:text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<path d="M21,7H7.462L5.91,3.586C5.748,3.229,5.392,3,5,3H2v2h2.356L9.09,15.414C9.252,15.771,9.608,16,10,16h8 c0.4,0,0.762-0.238,0.919-0.606l3-7c0.133-0.309,0.101-0.663-0.084-0.944C21.649,7.169,21.336,7,21,7z M17.341,14h-6.697L8.371,9 h11.112L17.341,14z" />
			<circle cx="10.5" cy="18.5" r="1.5" />
			<circle cx="17.5" cy="18.5" r="1.5" />
		</svg>
	</a>
	<span id='padloper_demo_cart_titles_amount' class="text-sm ml-1font-semibold bg-indigo-500  text-white rounded-full flex items-center justify-center"><span id="numberOfTitles"><?php echo $cartUniqueTitles; ?></span></span>