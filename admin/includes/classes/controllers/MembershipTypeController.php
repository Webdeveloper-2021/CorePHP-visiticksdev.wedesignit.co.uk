<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\MembershipType;

class MembershipTypeController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Membership Type ID not defined.');

        return new MembershipType($id);
    }
}