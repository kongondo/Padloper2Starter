# Padloper 2 Starter Site - DEMO 5

## Preamble

Please read the `README` in the [main branch](https://github.com/kongondo/Padloper2Starter) before using `Demo 5`. Ensure you read about the requirements and instructions.

## Demo

This is the Padloper **Demo 5**. The main difference between this and [Demo 1](https://github.com/kongondo/Padloper2Starter/tree/demo-1) is that `Demo 5` shows how you can have a checkout form with only a minimum number of inputs. In Demo 5, only email and country inputs are required.

Please note that this demo requires that you use a custom checkout form (as per its files) instead of the Padloper inbuilt one. This will give you the flexibility to amend inputs to the form.

## Files

### PHP

The custom checkout form used in this demo is defined in the [checkout template](/templates/checkout.php) under the variable `$customFormFields`. Please refer to the documentation on [custom checkout form](https://docs.kongondo.com/start/checkout/custom-customer-form.html#supported-form-inputs) for more details. As stated, the `name`s of the inputs must match the properties of `FieldtypePadloperOrderCustomer` in order to be processed and saved with the order.

The markup for the checkout form is in the partial template [checkout-form-html.php](/templates/partials/checkout-form-html.php). Specifically, it calles the render method `renderCheckoutFormCustomerDetails()` to get the markup from the file with the [customer details inputs](/templates/partials/checkout-form-customer-details-html.php).

MORE TBD

### JavaScript

TBD

### CSS

TBD

### Images

TBD

## Layout

Note that the underlying layout files for `Demo 5` differ slightly from those of `Demo 1`. In `Demo 5`, the checkout `form` spans across the whole grid layout to encompass the `order summary` column on the right. This is in order to include any inputs that you might wish to include in that grid column.

## Screenshots

*Minimal Checkout Form*

![minimal checkout form](/_screenshots/minimal_checkout_form.jpg)