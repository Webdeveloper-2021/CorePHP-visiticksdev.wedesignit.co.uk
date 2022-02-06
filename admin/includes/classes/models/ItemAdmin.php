<?php
namespace includes\classes\models;

use includes\classes\API;

class ItemAdmin extends BaseModel
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
        $requestresult = API::get("items/".$this->id);

        if($requestresult['ok'])
        {
            $this->itemType = $requestresult['content']->itemType;
            $this->name = $requestresult['content']->name;
            $this->description = $requestresult['content']->description;
            $this->imageFileName = $requestresult['content']->imageFileName;
            $this->categoryIds = $requestresult['content']->itemCategories;
            $this->membershipId = $requestresult['content']->membershipId ?? null;
            $this->eventId = $requestresult['content']->eventId ?? null;
            $this->membershipEventDiscountedItem = $requestresult['content']->membershipEventDiscountedItem ?? null;
            $this->itemDiscountMemberships = $requestresult['content']->itemDiscountMemberships;
            $this->sellOnline = $requestresult['content']->sellOnline;
            $this->onlinePrice = $requestresult['content']->onlinePrice;
            $this->sellAtPOS = $requestresult['content']->sellAtPOS;
            $this->posCode = $requestresult['content']->posCode;
            $this->POSPrice = $requestresult['content']->posPrice;
            $this->PointOfSaleBranchIds = $requestresult['content']->itemPointOfSaleBranches;
            $this->taxCodeId = $requestresult['content']->taxCodeId;
            $this->donationItem = $requestresult['content']->donationItem;
            $this->donationRateId = $requestresult['content']->donationRateId;
            $this->purchaseQuantityMinimum = $requestresult['content']->purchaseQuantityMinimum;
            $this->purchaseQuantityMaximum = $requestresult['content']->purchaseQuantityMaximum;
            $this->allowNegativeStock = $requestresult['content']->allowNegativeStock ?? null;
            $this->openingStock = $requestresult['content']->openingStock;
            $this->availableDateRange = $requestresult['content']->availableDateRange ?? null;
            $this->availableFrom = $requestresult['content']->availableFrom ?? null;
            $this->availableTo = $requestresult['content']->availableTo ?? null;

            return $requestresult['content'];
        }

        return false;
    }
}