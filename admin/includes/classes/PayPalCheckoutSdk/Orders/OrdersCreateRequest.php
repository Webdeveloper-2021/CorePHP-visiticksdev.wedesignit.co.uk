<?php

// This class was generated on Wed, 01 Aug 2018 16:35:39 PDT by version 0.1.0-dev+0ee05a-dirty of Braintree SDK Generator
// OrdersCreateRequest.php
// @version 0.1.0-dev+0ee05a-dirty
// @type request
// @data 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
// DO NOT EDIT

namespace includes\classes\PayPalCheckoutSdk\Orders;

use includes\classes\PayPalHttp\HttpRequest;

class OrdersCreateRequest extends HttpRequest
{
    function __construct()
    {
        parent::__construct("/v2/checkout/orders?", "POST");
        $this->headers["Content-Type"] = "application/json";
    }

    public function payPalPartnerAttributionId($payPalPartnerAttributionId)
    {
        $this->headers["PayPal-Partner-Attribution-Id"] = $payPalPartnerAttributionId;
    }
    public function prefer($prefer)
    {
        $this->headers["Prefer"] = $prefer;
    }
}
