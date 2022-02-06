<?php
namespace includes\classes\controllers;

use includes\classes\API;
use includes\classes\models\Order;

class OrderController extends BaseController {
    public function __construct()
    {
        parent::__construct();
    }

    public function index($sort = 'DESC') {
        $toDate = date('Ymd', strtotime('+1 day'));
        $requestresult = API::get("orders?customerId={$_SESSION['customer_id']}&fromDate=19000101&toDate={$toDate}&include=OrderLines&posSale=false");

        if($requestresult['ok'])
        {
            $orders = [];

            if (!empty($requestresult['content'])) {
                foreach($requestresult['content'] as $result) {
                    $orders[$result->createdAt] = new Order(null, $result);
                }
            }

            if ($sort == 'DESC')
                krsort($orders);

            return $orders;
        }

        return false;
    }

    public function view($id) {
        return new Order($id);
    }

    public function update($id) {
        // update order
    }
}