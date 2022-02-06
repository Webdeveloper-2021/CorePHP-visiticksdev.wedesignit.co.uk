<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\ReportingGroup;

class ReportingGroupController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Reporting Category Group ID not defined.');

        return new ReportingGroup($id);
    }
}