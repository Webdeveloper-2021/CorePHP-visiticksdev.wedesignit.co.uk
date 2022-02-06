<?php
namespace includes\classes\models;

use includes\classes\API;

class PosCodeAdmin extends BaseModel
{
    public $id;
    public $name;
    public $posCode;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("paymentmethods/".$this->id);

        if ($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;
            $this->posCode = $requestresult['content']->posCode;

            return $requestresult['content'];
        }

        return false;
    }
}