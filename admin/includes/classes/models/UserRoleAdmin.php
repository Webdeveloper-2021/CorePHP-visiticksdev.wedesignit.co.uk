<?php
namespace includes\classes\models;

use includes\classes\API;

class UserRoleAdmin extends BaseModel
{
    public $id;
    public $name;
    public $permissions;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("roles/".$this->id);

        if ($requestresult['ok'])
        {
            $this->name = $requestresult['content']->name;
            $this->rolePermissions = $requestresult['content']->rolePermissions;

            return $requestresult;
        }

        return false;
    }
}