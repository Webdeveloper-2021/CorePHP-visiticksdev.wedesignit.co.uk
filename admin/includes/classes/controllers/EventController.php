<?php
namespace includes\classes\controllers;

use includes\classes\API;
use includes\classes\models\Event;

class EventController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($from = null, $to = null) {
        if ($from && $to)
            return $this->_listForDateRange($from, $to);

        return $this->_list();
    }

    public function listSessionsForDateRange($from, $to) {
        $requestresult = API::get("eventsessions?fromDate={$from}&toDate={$to}&removeSalesStopIneligible=true");

        if($requestresult['ok'])
        {
            $events = [];

            if (!empty($requestresult['content'])) {
                foreach($requestresult['content'] as $result) {
                    $events[] = new Event(null, $result);
                }
            }

            return $events;
        }

        return false;
    }

    private function _list() {
        $requestresult = API::get("events?include=Items");

        if($requestresult['ok'])
        {
            $events = [];

            if (!empty($requestresult['content'])) {
                foreach($requestresult['content'] as $result) {
                    $events[] = new Event(null, $result);
                }
            }

            return $events;
        }

        return false;
    }

    private function _listForDateRange($from, $to) {
        $requestresult = API::get("events/date?fromDate={$from}&toDate={$to}");

        if($requestresult['ok'])
        {
            $events = [];

            if (!empty($requestresult['content'])) {
                foreach($requestresult['content'] as $result) {
                    $events[] = new Event(null, $result);
                }
            }

            return $events;
        }

        return false;
    }
}