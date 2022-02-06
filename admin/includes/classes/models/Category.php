<?php
namespace includes\classes\models;

use includes\classes\API;

class Category extends BaseModel
{
    public $id;
    public $name;
    public $description;
    public $visible;
    public $imageFileName;
    public $displayOrder;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("categories/".$this->id);

        if($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;
            $this->imageFileName = $requestresult['content']->imageFileName ?? null;
            $this->description = $requestresult['content']->description ?? null;
            $this->visible = $requestresult['content']->visible ?? false;
            $this->displayOrder = $requestresult['content']->displayOrder ?? 0;

            return $requestresult['content'];
        }

        return false;
    }

    public function items()
    {
        $requestresult = API::get("catalog/".$this->id);

        return $requestresult['ok'] ? $requestresult['content'] : [];
    }
}