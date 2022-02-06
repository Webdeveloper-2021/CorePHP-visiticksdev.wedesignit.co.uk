<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\PosCodeAdmin;

class PosCodeAdminController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Payment Method ID not defined.');

        return new PosCodeAdmin($id);
    }
}