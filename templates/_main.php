<?php

namespace ProcessWire;

# Padloper 2 Starter Site - DEMO 5

/**
 * _main.php
 * Main markup file (multi-language)

 * MULTI-LANGUAGE NOTE: Please see the README.txt file
 *
 * This file contains all the main markup for the site and outputs the regions
 * defined in the initialization (_init.php) file. These regions include:
 *
 *   $title: The page title/headline
 *   $content: The markup that appears in the main content/body copy column
 *   $sidebar: The markup that appears in the sidebar column
 *
 * Of course, you can add as many regions as you like, or choose not to use
 * them at all! This _init.php > [template].php > _main.php scheme is just
 * the methodology we chose to use in this particular site profile, and as you
 * dig deeper, you'll find many others ways to do the same thing.
 *
 * This file is automatically appended to all template files as a result of
 * $config->appendTemplateFile = '_main.php'; in /site/config.php.
 *
 * In any given template file, if you do not want this main markup file
 * included, go in your admin to Setup > Templates > [some-template] > and
 * click on the "Files" tab. Check the box to "Disable automatic append of
 * file _main.php". You would do this if you wanted to echo markup directly
 * from your template file or if you were using a template file for some other
 * kind of output like an RSS feed or sitemap.xml, for example.
 *
 *
 */

?>
<!DOCTYPE html>
<html lang="<?php echo _x('en', 'HTML language code'); ?>">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $page->summary; ?>" />
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>styles/tailwind.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates ?>styles/main.css" />
</head>

<body class="bg-white text-gray-600 work-sans leading-normal text-base tracking-normal">
	<!-- NAV -->
	<nav id="header" class="w-full z-30 top-0 py-1">
		<div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-6 py-3" x-data="Padloper2DemoData">
			<!-- TOP NAVIGATION -->
			<?php echo renderTopNavigation(); ?>
			<!-- END: TOP NAVIGATION -->
			<!-- SIDE CART -->
			<?php
			if ($isShowCart) {
				echo renderSideCart();
			}
			?>
			<!-- END: SIDE CART -->
		</div>
	</nav>

	<!-- END: NAV-->
	<!-- CAROUSEL -->
	<?php
	if ($isShowCarousel) {
		echo renderCarousel();
	}
	?>
	<!-- END: CAROUSEL -->

	<!-- MAIN -->
	<main id='main'>
		<section class="bg-white py-8">
			<div class="container mx-auto flex flex-wrap pt-4 pb-12">
				<!-- SECTION HEADER/ SECOND NAV -->
				<nav id="store" class="w-full top-0 px-6 py-1">
					<div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

						<?php

						$out = "";
						if (!empty($sectionURL)) {
							$sectionBackArrow = !empty($isShowSectionBackArrow) ? "&#8592;" : '';
							$out .= "
								<a class='uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl' href='{$sectionURL}'>{$sectionBackArrow}{$sectionHeader}</a>";
						} else {
							$out = "<span class='uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl'>{$sectionHeader}</span>";
						}


						if (!empty($sectionSubHeader)) {
							$out .= "<span>{$sectionSubHeader}</span>";
						}
						// ------
						echo $out;
						?>


					</div>
				</nav>

				<!-- CURRENT PAGE CONTENT -->
				<?php
				$out = "";
				if (empty($isShopPage)) {
					// special extra wrapper for 'non-shop' pages. e.g. 'basic-page'
					$out .= "<div id='main_content_wrapper' class='container mx-auto px-6 my-4'>{$content}</div>";
				} else {
					// else wrapper already taken care of in partial for shop pages
					$out = $content;
				}

				echo $out; ?>
				<!-- END: CURRENT PAGE CONTENT -->
			</div>
		</section>
	</main>
	<!-- END: MAIN -->

	<!-- FOOTER -->
	<?php echo renderFooter(); ?>
	<!-- END: FOOTER -->
	<!-- js -->
	<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
	<script src="<?php echo $config->urls->templates ?>scripts/main.js"></script>
	<script src="https://unpkg.com/htmx.org@1.6.1"></script>
</body>

</html>