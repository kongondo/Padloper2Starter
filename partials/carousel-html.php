<?php

namespace ProcessWire;
// ==================
// @TODO ADD THE LADIES?
// @TODO INHOUSE IMAGES!
// @todo @note: hardcoded images for now!
// @todo @note: 'view product' link is not implemented
// @todo: adapt and make dynamic for your use case or better, use dedicated JS carousel library
$slides = [
	[
		'id' => 1,
		'url' => $config->urls->templates . "images/home_slider_1.jpg",
		'slogan' => 'Accessories'
	],
	[
		'id' => 2,
		'url' => $config->urls->templates . "images/wooden_clock.jpg",
		'slogan' => "Real Bamboo Wall Clock",
	],

	[
		'id' => 3,
		'url' => $config->urls->templates . "images/old_diary.jpg",
		'slogan' => "Brown and blue hardbound book",
	],
	[
		'id' => 4,
		'url' => $config->urls->templates . "images/duvet.jpg",
		'slogan' => "Stripy Zig Zag Jigsaw Pillow and Duvet Set",
	],
	[
		'id' => 5,
		'url' => $config->urls->templates . "images/home_slider_5.jpg",
		'slogan' => 'Printed Matter'
	]

];

// alpine.js stuff
$xData = [
	'activeSlide' => 1,
	'slides' => $slides,
];
$script = "<script>Padloper2Demo=" . json_encode($xData) . ';</script>';

?>
<?php
// demo carousel data -> alpine.js
echo $script;
?>

<!-- CAROUSEL -->

<div class="carousel relative container mx-auto" style="max-width:1600px;" x-init='initCarouselData' x-data='Padloper2DemoData'>
	<div class="carousel-inner relative overflow-hidden w-full">
		<template x-for="slide in getCarouselSlides" :key="slide.id">
			<div x-show='isShow(slide.id)'>
				<div class="carousel-item" style="height:50vh;">
					<div class="block h-full w-full mx-auto flex pt-6 md:pt-0 md:items-center bg-cover bg-right" :style="{ backgroundImage: 'url(' + slide.url + ')' }">
						<div class="container mx-auto absolute">
							<div class="flex flex-col w-full lg:w-1/2 md:ml-16 items-center md:items-start px-6 tracking-wide">
								<p class="text-black text-2xl my-4" x-text='slide.slogan'></p>
								<a class="text-xl inline-block no-underline border-b border-gray-600 leading-relaxed hover:text-black hover:border-black" href="#"><?php echo __("view product"); ?></a>
							</div>
						</div>
					</div>
				</div>
				<label class="prev w-10 h-10 ml-2 md:ml-10 absolute cursor-pointer text-3xl font-bold text-black hover:text-white rounded-full bg-white hover:bg-gray-900 leading-tight text-center z-10 inset-y-0 left-0 my-auto" x-on:click="handleCarouselPreviousSlideNumber">‹</label>
				<label class="next w-10 h-10 mr-2 md:mr-10 absolute cursor-pointer text-3xl font-bold text-black hover:text-white rounded-full bg-white hover:bg-gray-900 leading-tight text-center z-10 inset-y-0 right-0 my-auto" x-on:click="handleCarouselNextSlideSlideNumber">›</label>
			</div>
		</template>

		<!-- Add additional indicators for each slide-->
		<ol class="carousel-indicators">
			<template x-for="slide in getCarouselSlides" :key="slide.id">
				<li class="inline-block mr-3">
					<label for="carousel-1" class="carousel-bullet cursor-pointer block text-4xl text-gray-400 hover:text-gray-900" x-on:click="setCarouselActiveSlideNumber(slide.id)">•</label>
				</li>
			</template>
		</ol>

	</div>
</div>