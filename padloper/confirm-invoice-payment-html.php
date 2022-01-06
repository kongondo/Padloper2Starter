<?php

namespace ProcessWire;

?>

<form action='<?php echo $invoiceUrl; ?>'>
    <?php
    // @todo: needed?
    echo $session->CSRF->renderInput();
    ?>
    <div id='confirm_invoice_payment_wrapper' class="container mx-auto px-6">
        <button class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded" value="1" type="submit"><?php echo __("Place Invoice Order"); ?></button>
    </div>
</form>