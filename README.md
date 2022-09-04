# Padloper 2 Starter Site - DEMO 4

## Preamble

Please read the `README` in the [main branch](https://github.com/kongondo/Padloper2Starter) before using `Demo 3`. Ensure you read about the requirements and instructions.

## Demo

This is the Padloper **Demo 4**. The main difference between this and [Demo 1](https://github.com/kongondo/Padloper2Starter/tree/demo-1) is that `Demo 4` shows how you can add a `Buy Now` button. This allows a *one-click-add-to-cart-and-go-to-checkout* experience. This is illustrated in the use case [mentioned here](https://processwire.com/talk/topic/27465-redirect-to-checkout-after-add-to-cart/) in the Padloper support forums.

Please note that this demo requires that you use a custom checkout form (as per its files) instead of the Padloper inbuilt one. This will give you the flexibility to add custom inputs to the form.

## Files

### PHP

Have a look at the file [product-price-and-add-to-cart-html.php](/templates/partials/product-price-and-add-to-cart-html.php) for the `Buy Now` button.

MORE TBD

### JavaScript

See `htmx:afterSettle` in [main.js](/templates/scripts/main.js) for redirect code.

MORE TBD

### CSS

TBD

### Images

TBD

## Layout

Note that the underlying layout files for `Demo 4` differ slightly from those of `Demo 1`. In `Demo 4`, the checkout `form` spans across the whole grid layout to encompass the `order summary` column on the right. This is in order to include any inputs that you might wish to include in that grid column.

## Screenshots

*Buy Now Button*

![buy now button](/_screenshots/buy_now_button.jpg)

*Demo Buy Now*

![buy now button](/_screenshots/buy_now_demo.gif)