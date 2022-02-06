<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\ItemAdmin;

class ItemAdminController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Item ID not defined.');

        return new ItemAdmin($id);
    }
}