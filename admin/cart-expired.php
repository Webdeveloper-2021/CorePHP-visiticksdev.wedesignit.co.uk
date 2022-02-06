<?php

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: CART EXPIRED///
///*****************************************************************************///
require("includes/visitickets.php");
use includes\classes\models\Cart;

$cart = new Cart();
$cart->expire();