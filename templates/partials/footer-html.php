<?php

namespace ProcessWire;
// ---------
/** @var WireData $shopSettings */
$shopSettings = $padloper->getShopGeneralSettings();
$shopName = $shopSettings->shop_name;
if (empty($shopName)) {
	$shopSettings = __("Nameless Shop");
}


?>

<footer id='footer' class="container mx-auto bg-white py-8 border-t border-gray-400">
	<div class="container">
		<div class="text-center">
			<div class="text-3xl mb-5 font-semibold"><a href="/" class="uppercase"><?php echo $shopName; ?></a></div>
			<nav class="footer_nav">
				<ul class="flex flex-wrap justify-center space-x-10 uppercase">
					<?php

					$navigationItems = [
						[
							'label' => __("Shop"),
							'url' =>  '/',
						],
						[
							'label' => __("clothes"),
							'url' =>  '/categories/clothes/'
						],
						[
							'label' => __("accessories"),
							'url' =>  '/categories/accessories/'
						],
						[
							'label' => __("lingerie"),
							'url' =>  '/categories/lingerie/'
						],
						[
							'label' => __("contact"),
							'url' =>  '#'
						],
					];

					$out = "";
					foreach ($navigationItems as $navigationItem) {
						$label = $navigationItem['label'];
						$url = $navigationItem['url'];
						$out .= "<li><a href='{$url}'>{$label}</a></li>";
					}
					// ---------
					echo $out;

					?>
				</ul>
			</nav>
			<div>
				<ul class="flex justify-center space-x-6 my-10">
					<?php
					$socials = [
						"pinterest",
						"linkedin",
						"instagram",
						"reddit-alien",
						"twitter"
					];
					$out = "";
					foreach ($socials as $social) {
						$out .= "<li><a href='#'><i class='fa fa-{$social}' aria-hidden='true'></i></a></li>";
					}
					echo $out;
					?>
				</ul>
			</div>
		</div>
	</div>

	<p class="flex justify-center space-x-6 text-sm">
		<a href='http://processwire.com'><?php echo __('Powered by ProcessWire CMS'); ?></a> &nbsp; / &nbsp;
		<?php
		if ($user->isLoggedin()) {
			// if user is logged in, show a logout link
			echo "<a href='{$config->urls->admin}login/logout/'>" . sprintf(__('Logout (%s)'), $user->name) . "</a>";
		} else {
			// if user not logged in, show a login link
			echo "<a href='{$config->urls->admin}'>" . __('Admin Login') . "</a>";
		}
		// output an "Edit" link if this page happens to be editable by the current user
		if ($page->editable()) {
			echo " &nbsp; / &nbsp;";
			echo "<a class='edit' href='$page->editUrl'>" . __('Edit') . "</a>";
		}
		?>
	</p>
</footer>
