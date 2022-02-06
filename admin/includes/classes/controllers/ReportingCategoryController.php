<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\ReportingCategory;

class ReportingCategoryController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Reporting Category ID not defined.');

        return new ReportingCategory($id);
    }
}