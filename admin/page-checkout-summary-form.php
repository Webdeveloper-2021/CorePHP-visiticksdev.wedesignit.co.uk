<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES///
///*****************************************************************************///

require("includes/visitickets.php");

use includes\classes\models\Customer;
use includes\classes\models\Order;
use includes\classes\models\Cart;
use includes\classes\PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use includes\classes\PayPalCheckoutSdk\Core\PayPalHttpClient;
use includes\classes\PayPalCheckoutSdk\Core\ProductionEnvironment;
use includes\classes\PayPalCheckoutSdk\Core\SandboxEnvironment;
use includes\classes\PayPalHttp\HttpException;
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;
use League\ISO3166\ISO3166;

$order = new Order;
$cart = new Cart;

if ($cart->itemCount < 1)
{
    echo json_encode(['redirect' => 'page-cart.php']);
    die();
}

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;
$error_msg = SETTING_GENERIC_ERROR_MSG;

$response = array(
    "completed" => "",
    "error" => ""
);

$showGiftAid = false;
$order_data = $_SESSION['order_data'];

if (!empty($cart->items))
{
    foreach ($cart->items as $item)
    {
        if ($item->unitDonationAmount > 0) {
            $showGiftAid = true;
            break;
        }
    }
}

if ($showGiftAid && !isset($_POST['giftaid'])) {
    $error = true;
    $error_msg = "Please select your gift aid preference.";
}

if (SETTING_TANDCREQUIRED && (!isset($_POST['tandc']) || $_POST['tandc'] !== 'YES')) {
    $error = true;
    $error_msg = "You must agree to our terms & conditions.";
}

if (!$error) {
    $_SESSION['order_data']['giftAid'] = $_POST['giftaid'] == 'YES';

///*****************************************************************************///
///BUYING FREE ITEM///
///*****************************************************************************///

    if ($uloggedin && !isset($order_data['orderPayments']) && $cart->total == 0 && $cart->itemCount === 1 && $cart->items[0]->item->membershipEventDiscountedItem) {
        $response["completed"] = true;

        echo json_encode($response);
        die();
    }

///*****************************************************************************///
///PAYPAL PAYMENT///
///*****************************************************************************///
    if ($order_data['orderPayments'][0]['paymentMethodId'] == 3) {
        $items = [];

        foreach ($cart->items as $item) {
            $items[] = [
                'name'        => $item->item->name,
                'description' => get_content_shortversion($item->item->description, 127),
                'quantity'    => $item->quantity,
                'unit_amount' => [
                    'value'         => $item->unitPrice,
                    'currency_code' => 'GBP'
                ],
                'category'    => 'DIGITAL_GOODS'
            ];
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent"              => "CAPTURE",
            "purchase_units"      => [
                [
                    "reference_id" => $_SESSION['order_reference'],
                    "amount"       => [
                        "value"         => $cart->total,
                        "currency_code" => "GBP",
                        "breakdown"     => [
                            "item_total" => [
                                "value"         => $cart->total,
                                'currency_code' => 'GBP'
                            ]
                        ]
                    ],
                    "items"        => $items,
                ]
            ],
            "application_context" => [
                "cancel_url"           => url_origin($_SERVER) . '/#cart',
                "return_url"           => url_origin($_SERVER) . '/#confirmation',
                'brand_name'           => SETTING_TITLE,
                'locale'               => 'en-GB',
                'landing_page'         => 'LOGIN',
                'shipping_preferences' => 'GET_FROM_FILE',
                'user_action'          => 'CONTINUE',
                'payment_method'       => [
                    'payer_selected'  => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                ],
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $env = PAYPAL_ENVIRONMENT == 'production' ? new ProductionEnvironment(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET) : new SandboxEnvironment(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET);
            $client = new PayPalHttpClient($env);
            $paypalResponse = $client->execute($request);

            $_SESSION['paypal_order_id'] = $paypalResponse->result->id;
            $response["redirectAway"] = $paypalResponse->result->links[1]->href;
        } catch (includes\classes\PayPalHttp\HttpException $ex) {
            $response["error"] = SETTING_GENERIC_ERROR_MSG;
            //dd($ex->statusCode, $ex->getMessage());
            // TODO: log errors
        }
    }

///*****************************************************************************///
///OPAYO PAYMENT///
///*****************************************************************************///
    if ($order_data['orderPayments'][0]['paymentMethodId'] == 1) {
        $gateway = OmniPay::create('SagePay\Direct')->initialize([
            'vendor'   => OPAYO_VENDORNAME,
            'testMode' => OPAYO_TESTMODE,
        ]);

        list($cardFirstName, $cardLastName) = explode(' ', $order_data['card']['name']);

        $card = new CreditCard([
            'firstName'        => $cardFirstName,
            'lastName'         => $cardLastName,
            'number'           => $order_data['card']['number'],
            'expiryMonth'      => $order_data['card']['expiry_month'],
            'expiryYear'       => $order_data['card']['expiry_year'],
            'CVV'              => $order_data['card']['cvv'],
            'billingFirstName' => $order_data['firstName'],
            'billingLastName'  => $order_data['lastName'],
            'billingAddress1'  => $order_data['addressLine1'],
            'billingAddress2'  => $order_data['addressLine2'],
            'billingCity'      => $order_data['addressLine3'],
            'billingPostcode'  => $order_data['postcode'],
            'billingCountry'   => (new ISO3166)->name($order_data['country'])['alpha2'],
            'billingPhone'     => $order_data['phone'],
            'email'            => $order_data['email'],
            //'clientIp' => $_SERVER['REMOTE_ADDR'],
            'shippingFirstName' => $order_data['firstName'],
            'shippingLastName'  => $order_data['lastName'],
            'shippingAddress1'  => $order_data['addressLine1'],
            'shippingAddress2'  => $order_data['addressLine2'],
            'shippingCity'      => $order_data['addressLine3'],
            'shippingPostcode'  => $order_data['postcode'],
            'shippingCountry'   => (new ISO3166)->name($order_data['country'])['alpha2'],
            'shippingPhone'     => $order_data['phone'],
        ]);

    // Create the minimal request message.

        $requestMessage = $gateway->purchase([
            'amount'        => $cart->total,
            'currency'      => 'GBP',
            'card'          => $card,
            'transactionId' => $_SESSION['order_reference'],
            'description'   => SETTING_TITLE,

            // If 3D Secure is enabled, then provide a return URL for
            // when the user comes back from 3D Secure authentication.

            'returnUrl'  => url_origin($_SERVER) . '/#confirmation',
            'failureUrl' => url_origin($_SERVER) . '/#cart',
        ]);

        $responseMessage = $requestMessage->send();

        if ($responseMessage->isSuccessful()) {
            $_SESSION['3dSecure'] = false;
            $response["completed"] = true;
        } elseif ($responseMessage->isRedirect()) {
            $response["redirectAwayPOST"] = true;
            $response["redirectAwayPOST_URL"] = $responseMessage->getRedirectUrl();
            $response["redirectAwayPOST_data"] = $responseMessage->getRedirectData();

            $_SESSION['3dSecure'] = true;
        } else {
            $reason = $responseMessage->getMessage(); // Log this

            $response["error"] = 'Something went wrong:<br>' . $reason;
        }
    }
} else {
    $response['error'] = $error_msg;
}

unset($_SESSION['order_completed']);

echo json_encode($response);