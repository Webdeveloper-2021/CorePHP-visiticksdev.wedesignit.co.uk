<?php
namespace includes\classes\models;

use includes\classes\API;

class ReportingGroup extends BaseModel
{
    public $id;
    public $name;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("reportcategorygroups/".$this->id);

        if ($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;

            return $requestresult['content'];
        }

        return false;
    }

    public function items()
    {
        $requestresult = API::get("reportcategorygroups/".$this->id);

        return $requestresult['ok'] ? $requestresult : [];
    }
}