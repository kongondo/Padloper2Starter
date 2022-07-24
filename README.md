# Padloper 2 Starter Site - DEMO 3

## Preamble

Please read the `README` in the [main branch](https://github.com/kongondo/Padloper2Starter) before using `Demo 3`. Ensure you read about the requirements and instructions.

## Demo

This is the Padloper **Demo 3**. The main difference between this and [Demo 2](https://github.com/kongondo/Padloper2Starter/tree/demo-2) is that `Demo 3` shows how you can add inputs for and collect and process VAT information from business customers during checkout. Specifically, how to allow business customers to add VAT information (hence exemption from certain taxes) at checkout and subsequently validate their VAT numbers.

In addition, `Demo 3` demonstrates how to use hooks to validate VAT numbers and amend the applicability of EU digital goods tax. This is illustrated in the use case [mentioned here](https://processwire.com/talk/topic/27271-eu-taxes-for-digital-products//) in the Padloper support forums. In this case, in addition to checking if a customer is based in the EU, if a line item is a digital product and if the shop's policy is to apply EU Digital Goods tax, the hook also checks:

- If customer is a business customer with a valid VAT Number for their stated (shipping) country.
- If customer shippping country and shop location are located in the same country.

Please note that this demo requires that you use a custom checkout form (as per its files) instead of the Padloper inbuilt one. This will give you the flexibility to add custom inputs to the form.

**Please note that it is your responsibility to ensure that your shop charges correct taxes per your country's regulations.**

## Hooks

### Check VAT Number Information for Business Customers

The hook `customCheckCustomOrderCustomerFormForErrors` in [ready.php](/ready.php) will xxxxxxx. This function hooks into `PadloperProcessOrder::getProductWeight`.


### Amend Applicability of EU Digital Goods Tax

xxxxxxx

To accomplish the above, we add the hook `customIsChargeEUDigitalGoodsTax` in [ready.php](/ready.php). This function hooks into `PadloperUtilities::isChargeEUDigitalGoodsTax`. xxxxxxx.

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

Note that the underlying layout files for `Demo 3` differ slightly from those of `Demo 1`. In `Demo 3`, the checkout `form` spans across the whole grid layout to encompass the `order summary` column on the right. This is in order to include any inputs that you might wish to include in that grid column.

## Screenshots

*Business Customer*

![business customer](/_screenshots/checkout_business_customer.jpg)