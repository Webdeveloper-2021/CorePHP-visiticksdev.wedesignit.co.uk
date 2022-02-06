<?php
namespace includes\classes\controllers;
use includes\classes\models\Cart;

class CartController extends BaseController {
    public function view() {
        // view cart
        return new Cart();
    }

    public function update($id) {
        // update order
    }
}