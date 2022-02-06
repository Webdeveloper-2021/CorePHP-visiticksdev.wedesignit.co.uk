<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\UserRoleAdmin;

class UserRoleAdminController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('User Role ID not defined.');

        return new UserRoleAdmin($id);
    }
}