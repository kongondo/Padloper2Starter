# Padloper 2 Starter Site - DEMO 2

## Preamble

Please read the `README` in the [main branch](https://github.com/kongondo/Padloper2Starter) before using `Demo 2`. Ensure you read about the requirements and instructions.

## Demo

This is the Padloper **Demo 2**. The main difference between this and [Demo 1](https://github.com/kongondo/Padloper2Starter/tree/demo-1) is that `Demo 2` shows how you can add inputs for and collect and process extra information from customers during checkout. Specifically, how to allow customers to customise their ordered products at checkout and subsequently view the requests in your Padloper backend.

In addition, `Demo 2` demonstrates how to use a custom field and hook to amend calculation of `order` weight for product variants. This is useful for cases where the weight of the cart will vary depending on the selected variant of a product. This is illustrated in the use case [mentioned here](https://processwire.com/talk/topic/27348-weight-for-shipping-calculation-different-depending-of-the-variant/) in the Padloper support forums.

## Fields

This demo requires that you create the following 3 custom fields and add this to the specified templates. Please use the `fields_data.json` found in the [_fields folder](/_fields/) with the ProcssWire fields imports feature to create these fields.

1. `custom_weight`: Add this `FieldtypeDecimal` field to the Padloper template for product variants, i.e. `padloper-product-variant`. Editors will fill this when editing product variants that need a custom weight. A hook in [ready.php](/ready.php) will be used to amend the weight during shipping rate calculation.
2. `product_is_customisable`: Add this `FieldtypeCheckbox` field to the Padloper templates for products and product variants, i.e. `padloper-product` and `padloper-product-variant`. Editors will tick this checkbox for products that they want to allow customisation on during checkout.
3. `product_customise_details`: Add this `InputfieldTextarea` (plain) field to the Padloper template for product order line items, i.e. `padloper-order-line-item`. A hook in [ready.php](/ready.php) will be used to process, sanitize and save custom product instructions from the customer. These will be stored in this field. The frontend textarea to collect the customer data is built in the [product customise details](/templates/partials/checkout-form-customisable-product-details-html.php) template partial. Finally, a hook in [ready.php](/ready.php) will be used to display the custom instructions to the shop admin when they are viewing the order.

## Hooks

### Amend Product Weight

The hook `customProductWeight` in [ready.php](/ready.php) will amend the `unit weight` of a product variant if the variant has specified a custom weight. This function hooks into `PadloperUtilities::getProductWeight`.

Please note that if you want to amend the whole weight of the cart, it is possible to hook into `PadloperUtilities::getOrderWeight` instead.

### Save Product Customisation Details

The hook `processOrderProductsCustomisation` in [ready.php](/ready.php) will process, sanitize and save product customisation details related to line items in an order. This function hooks into `PadloperProcessOrder::orderSaved`.

>Please note that the implentation does not take into account multiple quantities of the same line item as separate customisations.

### Display Product Customisation Details in Order

There are several places we can hook into to customise the markup of the Padloper `view order backend`. It depends on the parts of the GUI that we want to change. In `Demo 2`, we will only change the markup that renders the title of the product that a line item represents. We will append product customisation details after the title, if applicable.

To accomplish the above, we add the hook `customOrderLineItemTableRow` in [ready.php](/ready.php). This function hooks into `PadloperProcessRenderOrders::getSingleViewTableRow`. It amends each applicable individual row in the table that displays line items in an order in `a single order view` dashboard.

## Files

### PHP

TBD


### JavaScript

TBD

### CSS

TBD

### Images

TBD

## Screenshots
