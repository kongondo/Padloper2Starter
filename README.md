# Padloper 2 Starter Site

This repository contains templates, partials, assets and scripts to help you quickly get started with Padloper 2.

## Requirements

A copy of Padloper 2.

## Notes

This is not meant to be a full-blown site. In addition, the styling is incomplete and the original images need to be optimised.

## Instructions

Add everything inside your <code>/site/templates/</code>

MORE TBD

### Templates and Template (Partials) Files

See <code>/partials/</code> folder for templates rendered as partials in other templates. Also see function <code>getPartialTemplate()</code> in <code>\_func.php</code>

The <code>/padloper/</code> are custom Padloper template render files specifically for checkout process.

Enable **URL Segments** on the <code>products</code> and <code>categories</code> templates.

TBD

### Images

<code>/images/</code> folder contains images (hardcoded for now) for the slider on the homepage.

TBD

### JavaScript

- htmx
- alpine.js

@note: above two from CDN @see <code>\_main.php</code>
@see home.php for how htmx requests of cart manipulation are handled.

@see <code>main.js</code> for setting alpine.js and htmx configs. Also for handling htmx or jQuery ajax requests.

TBD

### CSS

Tailwind CSS

@TODO: CSS needs minification and maybe could have done more purging?

TBD

### Pages

You need to create a **products** and a **categories** page under your root page (home). They should use the similarly named templates respectively.

The template files for above pages are:

<code>products.php</code> -> for all or single product
<code>categories.php</code> -> for all or single category

You will also need a checkout page. Please follow the [instructions](https://docs.kongondo.com/start/checkout/checkout-process.html) in the Padloper documentation for setting that up.

@note: shop pages (exports), e.g. products not (yet?) included in this repo.

TBD

## Credits

TBD
