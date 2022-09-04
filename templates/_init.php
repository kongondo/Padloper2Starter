<?php

namespace ProcessWire;

# Padloper 2 Starter Site - DEMO 3

/**
 * Initialize variables output in _main.php
 *
 * Values populated to these may be changed as desired by each template file.
 * You can setup as many such variables as you'd like.
 *
 * This file is automatically prepended to all template files as a result of:
 * $config->prependTemplateFile = '_init.php'; in /site/config.php.
 *
 * If you want to disable this automatic inclusion for any given template,
 * go in your admin to Setup > Templates > [some-template] and click on the
 * "Files" tab. Check the box to "Disable automatic prepend file".
 *
 */

// Variables for regions we will populate in _main.php
// Here we also assign default values for each of them.
$title = $page->get('headline|title');
$content = $page->body;
// @note: currently not in use
$sidebar = $page->sidebar;

// the section just below the main nav or carousel
$sectionHeader = __("Store");
$sectionSubHeader = "";
$sectionURL = "/products/";

// -----------
// if to show carousel
$isShowCarousel = false;
// if to show carousel
$isShowSectionBackArrow = false;
// if to show side cart
$isShowCart = true;
// for non-shop pages (e.g. 'basic-page' might need extra markup)
$isShopPage = true;

// ---------
// We refer to our homepage a few times in our site, so
// we preload a copy here in $homepage for convenience.
$homepage = $pages->get('/');

// Include shared functions
include_once("./_func.php");
