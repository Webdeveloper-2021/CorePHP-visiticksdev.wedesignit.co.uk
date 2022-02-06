<?php
namespace includes\classes\models;

use includes\classes\API;

class Cart extends BaseModel
{
    public $id;
    public $items = [];
    public $expiresAt = null;
    public $hasExpired = false;
    public $itemCount = 0;
    public $total = 0;
    public $error = false;

    public function __construct()
    {
        parent::__construct();

        if (isset($_SESSION['cart_id']) && !empty($_SESSION['cart_id'])) {
            if (!$this->_getFromAPI())
                $this->_createInAPI();

            $this->_loadLocal();

            if ($this->hasExpired) {
                $_SESSION['cart_expired'] = true;

                $this->expire();
            }
        }
    }

    public function addItems($items) {
        // create cart if not yet exists
        if (!isset($_SESSION['cart_id']) || empty($_SESSION['cart_id'])) {
            $this->_createInAPI();
            $this->_loadLocal();
        }

        $requestresult = API::post("baskets/item", [
            "basketId" => $this->id,
            "items" => $items
        ]);

        if ($requestresult['ok'])
        {
            return true;
        }

        $this->error = $requestresult['httpCode'] ?? 0;

        return false;
    }

    public function updateItem($id, $qty) {
        $requestresult = API::post("baskets/item/setquantity", [
            'quantity' => intval($qty),
            'basketItemId' => $id
        ]);

        if ($requestresult['ok'])
        {
            return true;
        }

        $this->error = $requestresult['httpCode'] ?? 0;

        return false;
    }

    public function removeItem($id) {
        $requestresult = API::del("baskets/item/" . $id);

        if ($requestresult['ok'])
        {
            $this->refresh();

            if ($this->itemCount < 1) {
                $this->expire();
            }

            return true;
        }

        $this->error = $requestresult['httpCode'] ?? 0;

        return false;
    }

    public function expire() {
        unset($_SESSION['cart_id'], $_SESSION['cart'], $_SESSION['tempEventTickets'], $_SESSION['tempEventMemberships'], $_SESSION['order_data'], $_SESSION['tempEventWithMemberships'], $_SESSION['memberships_data']);
    }

    public function willExpireSoon() {
        if ($this->expiresAt - time() < SETTING_CHECKOUTBASKETEXTEND * 60) {
            return true;
        }

        return false;
    }

    public function refresh($add_time = false) {
        if ($add_time && $this->willExpireSoon())
            $this->_extendLifetime();

        $this->_getFromAPI();
        $this->_loadLocal();
    }

    public function hasMemberships() {
        foreach ($this->items as $item) {
            if ($item->item->itemType === 4 && (!isset($item->renewalMembershipSubscriptionId) || !$item->renewalMembershipSubscriptionId)) // itemType 4 = membership
               return true;
        }

        return false;
    }

    public function memberships() {
        $memberships = [];

        foreach ($this->items as $item) {
            if ($item->item->itemType === 4 && (!isset($item->renewalMembershipSubscriptionId) || !$item->renewalMembershipSubscriptionId)) // itemType 4 = membership
                for ($i = 0; $i < $item->quantity; $i++)
                    $memberships[] = $item;
        }

        return $memberships;
    }

    private function _createInAPI() {
        $requestresult = API::post("baskets", [
            "soldAtPOS" => false
        ]);

        if ($requestresult['ok'])
        {
            $_SESSION['cart_id'] = $requestresult['content']->id;
            unset($_SESSION['cart_expired']);

            return $this->_getFromAPI();
        }

        return false;
    }

    private function _getFromAPI() {
        $requestresult = API::get("baskets/{$_SESSION['cart_id']}?include=BasketItems,BasketItems.Item,BasketItems.Item.Event,BasketItems.Item.Membership");

        if ($requestresult['ok'])
        {
            $requestresult['content']->total = $this->_getTotalFromAPI();
            $requestresult['content']->expiresAt = time() + ($requestresult['content']->expiresInHours * 60 * 60) + ($requestresult['content']->expiresInMinutes * 60) + $requestresult['content']->expiresInSeconds;
            $_SESSION['cart'] = $requestresult['content'];

            return $requestresult['content'];
        }

        return false;
    }

    private function _getTotalFromAPI() {
        $requestresult = API::get("baskets/total/{$_SESSION['cart_id']}");

        return $requestresult['ok'] ? $requestresult['content']->totalIncludingTax : false;
    }

    private function _extendLifetime() {
        $requestresult = API::post("baskets/addtime", [
            "basketId" => $this->id,
            "seconds" => time() + SETTING_CHECKOUTBASKETEXTEND * 60 - $this->expiresAt
        ]);

        return $requestresult['ok'];
    }

    private function _loadLocal() {
        if ($data = $_SESSION['cart']) {
            $this->id = $data->id;
            $this->items = $data->basketItems;
            $this->expiresAt = $data->expiresAt;
            $this->hasExpired = $data->hasExpired;
            $this->itemCount = count($data->basketItems);
            $this->total = $data->total;
        }
    }
}