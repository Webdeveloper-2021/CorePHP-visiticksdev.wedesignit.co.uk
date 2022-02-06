<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\UserAdmin;

class UserAdminController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('User ID not defined.');

        return new UserAdmin($id);
    }
}