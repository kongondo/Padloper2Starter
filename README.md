# Padloper 2 Starter Site - DEMO 6

## Preamble

Please read the `README` in the [main branch](https://github.com/kongondo/Padloper2Starter) before using `Demo 6`. Ensure you read about the requirements and instructions.

## Demo

This is the Padloper **Demo 6**. This demo shows how you can set initial customer details to the checkout form values at order confirmation using hooks, i.e. a pre-filled form.

Please note that this demo can be used with both the inbuilt Padloper checkout form or any custom checkout form.

## Hooks

### Set Initial Order Customer Checkout Form Values

The hook `customGetOrderCustomer` in [ready.php](/ready.php) will set initial order customer checkout form values for a **pretend customer** who is logged in. This function hooks into `PadloperProcessOrder::getOrderCustomer`. *Please note the check for login status and retrieval of the initial customer details is not implemented in the demo*. You will need to code such logic yourself. The hook only kicks in once; before an order page has been created. A use case is [illustrated here](https://processwire.com/talk/topic/27599-setting-initial-customer-form-values-at-confirmation-using-inbuilt-form/) in the Padloper support forums.


## Files

### PHP

TBD


### JavaScript

TBD

### CSS

TBD

### Images

TBD

## Layout

Note that the underlying layout files for `Demo 6` differ slightly from those of `Demo 1`. In `Demo 6`, the checkout `form` spans across the whole grid layout to encompass the `order summary` column on the right. This is in order to include any inputs that you might wish to include in that grid column.

## Screenshots

*Pre-filled (initial) Customer Checkout Form*

![business customer](/_screenshots/checkout_form_with_initial_customer_details.jpg)