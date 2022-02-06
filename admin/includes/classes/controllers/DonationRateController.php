<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\DonationRate;

class DonationRateController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Donation Rate ID not defined.');

        return new DonationRate($id);
    }
}