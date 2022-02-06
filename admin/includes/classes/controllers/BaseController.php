<?php
namespace includes\classes\controllers;

class BaseController
{
    public $U_SESSION_API_TOKEN;
    public $U_DATE;
    public $U_DATETIME;

    public function __construct()
    {
        global $U_SESSION_API_TOKEN, $U_DATE, $U_DATETIME;

        $this->U_SESSION_API_TOKEN = $U_SESSION_API_TOKEN;
        $this->U_DATE = $U_DATE;
        $this->U_DATETIME = $U_DATETIME;
    }
}