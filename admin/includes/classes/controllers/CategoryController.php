<?php
namespace includes\classes\controllers;

use http\Exception\InvalidArgumentException;
use includes\classes\models\Category;

class CategoryController extends BaseController
{
    public function view($id) {
        if (!$id)
            throw new InvalidArgumentException('Category ID not defined.');

        return new Category($id);
    }
}