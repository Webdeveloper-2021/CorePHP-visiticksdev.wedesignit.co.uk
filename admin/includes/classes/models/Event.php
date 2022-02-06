<?php
namespace includes\classes\models;

use includes\classes\API;

class Event extends BaseModel
{
    public $id;
    public $name;
    public $description;
    public $items = [];
    public $changeSessionTimeLimitType; // 1 - beforeEventStart; 2 - afterEventStart
    public $hasMembershipEventDiscountedItem = false;
    public $minPrice = null;
    public $sessions = [];
    public $date;
    public $memberships;
    public $validMemberships;
    public $itemsForMemberships = [];

    public function __construct($id = null, $data = [])
    {
        parent::__construct();

        if ($id) {
            $this->id = $id;

            $data = $this->_getFromAPI();
        }

        $this->setup($data);
    }

    private function _getFromAPI() {
        $requestresult = API::get("events/".$this->id."?include=Items.DonationRate,Items.ItemDiscountMemberships.Membership.Items.DonationRate");

        return $requestresult['ok'] ? $requestresult['content'] : false;
    }

    public function getSession($id) {
        $requestresult = API::get("eventsessions/".$id);

        return $requestresult['ok'] ? $requestresult : false;
    }

    public function getSessions($from = null, $to = null) {
        $from = $from ?: date('Ymd');
        $to = $to ?: date('Ymd', strtotime('+5 years'));

        $requestresult = API::get("eventsessions?eventId=".$this->id."&fromDate={$from}&toDate={$to}&removeSalesStopIneligible=true");

        if ($requestresult['ok'])
        {
            foreach($requestresult['content'] as $session) {
                $date = date('Ymd', strtotime($session->date));
                $startTime = '';
                $endTime = '';

                if (isset($session->startHour)) $startTime = sprintf("%02d", $session->startHour);
                if (isset($session->startMinute)) $startTime .= ':' . sprintf("%02d", $session->startMinute);

                if (isset($session->endHour)) $endTime = sprintf("%02d", $session->endHour);
                if (isset($session->endMinute)) $endTime .= ':' . sprintf("%02d", $session->endMinute);

                $this->sessions[$date]['times'][] = [
                    'eventSessionId'    => $session->id,
                    'totalCapacity'     => $session->capacity ?? 1,
                    'availableCapacity' => $session->availableCapacity->quantity ?? 1,
                    'startHour'         => $session->startHour ?? null,
                    'startMinute'       => $session->startMinute ?? null,
                    'endHour'           => $session->endHour ?? null,
                    'endMinute'         => $session->endMinute ?? null,
                    'startTime'         => $startTime,
                    'endTime'           => $endTime
                ];

            }

            return $this->sessions;
        }

        return false;
    }

    public function setup($data) {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->description = $data->description ?? null;
        $this->items = $data->items ?? [];
        $this->date = isset($data->date) ? $data->date : null;

        $prices = [];
        $validMembershipsIds = [];
        $memberships = [];

        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item->onlinePrice > 0)
                    $prices[] = $item->onlinePrice;

                if (isset($item->membershipEventDiscountedItem) && $item->membershipEventDiscountedItem === true && isset($item->itemDiscountMemberships)) {
                    $this->hasMembershipEventDiscountedItem = true;

                    foreach ($item->itemDiscountMemberships as $membership) {
                        $validMembershipsIds[] = $membership->membershipId;
                        $memberships[] = $membership->membership;
                    }

                    $this->itemsForMemberships[] = $item;
                }
            }
        }

        $this->minPrice = !empty($prices) ? min($prices) : null;
        $this->validMemberships = $validMembershipsIds;
        $this->memberships = $memberships;
    }
}