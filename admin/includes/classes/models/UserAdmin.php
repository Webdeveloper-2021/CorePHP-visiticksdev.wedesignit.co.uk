<?php
namespace includes\classes\models;

use includes\classes\API;

class UserAdmin extends BaseModel
{
    public $id;
    public $userName;
    public $fullName;
    public $password;
    public $userRoles;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("users/admin/".$this->id);

        if ($requestresult['ok'])
        {
            $this->itemType = $requestresult['content']->itemType;
            $this->userName = $requestresult['content']->userName;
            $this->fullName = $requestresult['content']->fullName;
            $this->password = $requestresult['content']->password;
            $this->userRoles = $requestresult['content']->userRoles;

            return $requestresult['content'];
        }

        return false;
    }
}