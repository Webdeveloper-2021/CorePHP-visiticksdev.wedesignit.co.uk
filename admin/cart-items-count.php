<?php

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: CART ITEMS COUNT ///
///*****************************************************************************///
require("includes/visitickets.php");
use includes\classes\models\Cart;

$cart = new Cart();

$count = 0;

if (!empty($cart->items)) {
    foreach ($cart->items as $item) {
        $count += $item->quantity;
    }
}

echo json_encode([
    'cartItemsCount' => $count
]);