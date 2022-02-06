<?php
namespace includes\classes\models;

use includes\classes\API;

class Item extends BaseModel
{
    public $id;
    public $itemId;
    public $unitPrice;
    public $unitDonationAmount;
    public $item;

    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->itemId = $id;

        return $this->_getFromAPI();
    }

    private function _getFromAPI() {
        $requestresult = API::get("items/".$this->id."?include=DonationRate");

        if($requestresult['ok'])
        {
            $this->unitPrice = price($requestresult['content']->onlinePrice);
            $donation_amount = 0;

            if (!show_prices_without_donation()) {
                if (!$requestresult['content']->donationItem && isset($requestresult['content']->donationRate)) {
                    if ($requestresult['content']->donationRate->type === 1 && isset($requestresult['content']->donationRate->amount) && $requestresult['content']->donationRate->amount > 0) { // fixed amount donation rate
                        $donation_amount = price($requestresult['content']->donationRate->amount);
                    }

                    if ($requestresult['content']->donationRate->type === 2 && isset($requestresult['content']->donationRate->percentage) && $requestresult['content']->donationRate->percentage > 0) { // percentage donation rate
                        $donation_amount = price($requestresult['content']->onlinePrice / 100 * $requestresult['content']->donationRate->percentage);

                        if (isset($requestresult['content']->donationRate->roundUpTo) && $requestresult['content']->donationRate->roundUpTo > 0) {
                            $donation_amount = ceil($donation_amount / $requestresult['content']->donationRate->roundUpTo) * $requestresult['content']->donationRate->roundUpTo;
                        }
                    }
                }
            }

            $this->unitPrice = price($requestresult['content']->onlinePrice + $donation_amount);
            $this->unitDonationAmount = $donation_amount;

            if ($requestresult['content']->donationItem) {
                $this->unitDonationAmount = $this->unitPrice;
            }

            $this->item = $requestresult['content'];

            return $this;
        }

        return false;
    }
}