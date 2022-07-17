# Padloper 2 Starter Site

This repository contains various demos for [Padloper](https://padloper.pw/) (aka Padloper 2.). Each branch is a standalone `demo`. This can be based on a feature (e.g. product customisation at checkout) or a technology (htmx, hyperscript, jQuery, etc.).

Please read this main **README** before attempting to use the demos.

The main branch itself does not have any demos. Instead, its purpose is to:

1. Outline a high-level intro to the demos.
2. Outline requirements, generic notes and instructions.
3. List and summarise what the individual demos contain.

## Preamble

This repository contains templates, partials, assets and scripts to help you quickly get started with Padloper 2 organised around various demos. It is advisable to go through the [Padloper documentation](https://docs.kongondo.com/), at least the [getting started](https://docs.kongondo.com/start/) section.

## Requirements

1. A copy of Padloper.
2. Demo-specific requirements, e.g. some Third-Party JavaScript library.

## Notes

This is not meant to be a full-blown site. In addition, the styling is incomplete and the original images need to be optimised.

## Folder Structure

Demo sites mimick a ProcessWire `site` folder structure without the folders `assets` and `modules` and without the files `.htaccess` and `config.php`. Folders whose names are prefixed with an underscore should not be added to your site. They serve several purposes, e.g. store screenshots, i.e. `_screenshots` or fields data, i.e., `_fields`, etc.

## Instructions

Add everything to your <code>/site/</code> folder making sure to ignore folders whose names start with an underscore, e.g., `_somefolder`.

### Fields

ProcessWire `fields` required for a demo to work properly will have their `JSON configs` included in a `_fields` folder at the root of the demo. That folder is not for deployment. Instead, use the file `fields_data.json` inside the folder to import the required fields into your ProcessWire site. You can then edit the fields if you wish, e.g. change the labels, descriptions, etc.

### Templates and Template (Partials) Files

See <code>/templates/partials/</code> folder for templates rendered as partials in other templates. Also see the function <code>getPartialTemplate()</code> in <code>/templates/_func.php</code>.

The `php` files inside <code>/templates/padloper/</code> are custom Padloper template render files specifically for checkout process.

Make sure to enable **URL Segments** on the <code>products</code> and <code>categories</code> templates on your site.

### Images

<code>/templates/images/</code> folder contains images (hardcoded for now) for a demo. For instance, for `DEMO 1`, the slider on the homepage.

### JavaScript

The different demos might utilise third-party JavaScript libraries, for instance.

- [htmx](https://htmx.org/)
- [alpine.js](https://alpinejs.dev/)
- [hyperscript](https://hyperscript.org/)
- jQuery

When used, unless stated otherwise, these will be pulled from their respective CDNs from within `/templates/_main.php`.

Custom JavaScript code if used in a demo will be available in the file `/templates/scripts/main.js`. Custom JavaScript code are not minimised.

### CSS

Most of the demos use [Tailwind CSS](https://tailwindcss.com/). If a different CSS Framework is used, this will be stated in the demo's README. The file containing the Tailwind CSS will be available in the file `/templates/styles/tailwind.css`. Tailwind CSS files are not minimised and might also require purging.

Custom CSS code if used in a demo will be available in the file `/templates/styles/main.css`. Custom CSS code are not minimised.

### Pages

You need to create a **products** and a **categories** page under your root page (home). They should use the similarly named templates respectively.

The template files for above pages are:

<code>/templates/products.php</code> -> for all or single product
<code>/templates/categories.php</code> -> for all or single category

You will also need a checkout page. Please follow the [instructions](https://docs.kongondo.com/start/checkout/checkout-process.html) in the Padloper documentation for setting that up.

>@note: shop pages (exports), e.g. products not included in this repo.

## Credits

Images: [Pixabay](https://pixabay.com/) and [Unsplash](https://unsplash.com/).

## Demos

Unless stated otherwise, all demos use a `partial templates` approach. This means using ProcessWire [TemplateFile](https://processwire.com/api/ref/template-file/) Class to render micro/partial templates within other templates. This increases the flexibility of the template strategy.

### Demo 1

This is the main Padloper [demo](https://github.com/kongondo/Padloper2Starter/tree/demo-1). It is a complete demonstration of a Padloper shop frontend including products and categories/collections display, single product display, add products to checkout and checkout process. It utilises `htmx` and `alpine.js` in various places including checkout. It uses an `ajax-powered` checkout via `htmx`. This means ajax responses are `html`.


#### Demo 1-jquery

This is identical to `Demo 1` except that checkout ajax is powered by `jQuery` instead of `htmx`. This means that responses to ajax are `json`.

#### Demo 1-non-ajax

This is identical to `Demo 1` except that checkout is not ajax-powered. Hence, it doesn't use `htmx`.


### Demo 2

This is similar to demo
