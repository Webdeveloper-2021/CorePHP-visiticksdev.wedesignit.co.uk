<?php
namespace includes\classes\models;

use includes\classes\API;

class ReportingCategory extends BaseModel
{
    public $id;
    public $name;
    public $reportCategoryGroupId;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("reportcategories/".$this->id);

        if ($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;
            $this->reportCategoryGroupId = $requestresult['content']->reportCategoryGroupId ?? null;

            return $requestresult['content'];
        }

        return false;
    }

    public function items()
    {
        $requestresult = API::get("reportcategories/".$this->id);

        return $requestresult['ok'] ? $requestresult : [];
    }
}