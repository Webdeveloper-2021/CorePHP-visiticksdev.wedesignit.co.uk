<?php
namespace includes\classes\models;

use includes\classes\API;

class Order extends BaseModel
{
    public $error;
    public $errors;
    public $id;
    public $reference;
    public $orderLines = [];
    public $billingContact = [];
    public $billingAddress = [];
    public $createdAt;
    public $total = 0;
    public $customerId;
    public $orderPayments;

    public function __construct($id = null, $data = [])
    {
        parent::__construct();

        if ($id)
            $this->get($id);
        else
            $this->setup($data);
    }

    public function create($data, $validateOnly = false) {
        if ($validateOnly)
            $data['validateOnly'] = true;

        $requestresult = API::post("orders", $data);

        if($requestresult['ok'])
        {
            $_SESSION['order_reference'] = $requestresult['content']->reference;
            $this->reference = $requestresult['content']->reference;

            return $validateOnly ? true : $requestresult['content']->id;
        }

        if (!empty($requestresult['content'])) {
            foreach ($requestresult['content'] as $result) {
                $this->errors[] = $result->message;
            }
        }

        return false;
    }

    public function sendConfirmationEmail() {
        if ($this->customerId === $_SESSION['customer_id']) {
            $requestresult = API::post("orders/sendconfirmationemail", [
                "orderId" => $this->id
            ]);

            if ($requestresult['ok'])
            {
                return true;
            }

            $this->error = $requestresult['httpCode'] ?? 0;

            return false;
        }

        return false;
    }

    public function changeLineEventSession($lineId, $sessionId) {
        $requestresult = API::post("orders/line/changesession", [
            "orderLineId" => $lineId,
            'eventSessionId' => $sessionId
        ]);

        if ($requestresult['ok'])
        {
            return true;
        }

        $this->error = $requestresult['httpCode'] ?? 0;

        return false;
    }

    private function get($id) {
        $requestresult = API::get("orders/{$id}?include=BillingContact,BillingAddress,OrderLines,OrderLines.Item,OrderLines.EventSession.Event,OrderPayments");

        return !$requestresult['ok'] ? false : $this->setup($requestresult['content']);
    }

    private function setup($data) {
        $this->id = $data->id;
        $this->reference = $data->reference;
        $this->orderLines = $data->orderLines;
        $this->createdAt = $data->createdAt;
        $this->billingContact = $data->billingContact ?? [];
        $this->billingAddress = $data->billingAddress ?? [];
        $this->customerId = $data->customerId;
        $this->orderPayments = $data->orderPayments;

        if (!empty($this->orderLines)) {
            foreach ($this->orderLines as $line) {
                $this->total += $line->quantity * $line->unitPriceIncludingTax;
            }
        }
    }
}