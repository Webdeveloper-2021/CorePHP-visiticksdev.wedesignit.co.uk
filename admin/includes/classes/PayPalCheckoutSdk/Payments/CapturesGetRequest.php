<?php

// This class was generated on Wed, 01 Aug 2018 16:35:04 PDT by version 0.1.0-dev+0ee05a-dirty of Braintree SDK Generator
// CapturesGetRequest.php
// @version 0.1.0-dev+0ee05a-dirty
// @type request
// @data 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
// DO NOT EDIT

namespace includes\classes\PayPalCheckoutSdk\Payments;

use includes\classes\PayPalHttp\HttpRequest;

class CapturesGetRequest extends HttpRequest
{
    function __construct($captureId)
    {
        parent::__construct("/v2/payments/captures/{capture_id}?", "GET");

        $this->path = str_replace("{capture_id}", urlencode($captureId), $this->path);
        $this->headers["Content-Type"] = "application/json";
    }


}
