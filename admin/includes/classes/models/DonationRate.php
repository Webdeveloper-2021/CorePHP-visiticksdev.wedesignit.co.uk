<?php
namespace includes\classes\models;

use includes\classes\API;

class DonationRate extends BaseModel
{
    public $id;
    public $type;
    public $name;
    public $amount;
    public $percentage;
    public $roundUpTo;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->_getFromAPI();
    }

    private function _getFromAPI()
    {
        $requestresult = API::get("donationrates/".$this->id);

        if ($requestresult['ok'])
        {
            $this->type = $requestresult['content']->type ?? 1;
            $this->name = $requestresult['content']->name;
            $this->amount = $requestresult['content']->amount;
            $this->percentage = $requestresult['content']->percentage;
            $this->roundUpTo = $requestresult['content']->roundUpTo ?? 0;

            return $requestresult['content'];
        }

        return false;
    }

    public function items()
    {
        $requestresult = API::get("donationrates/".$this->id);

        return $requestresult['ok'] ? $requestresult['content'] : [];
    }
}