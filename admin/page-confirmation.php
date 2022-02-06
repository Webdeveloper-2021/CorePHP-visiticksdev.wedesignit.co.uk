<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

	header('Content-Type: application/json');
//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

    use includes\classes\models\Customer;
    use includes\classes\PayPalCheckoutSdk\Core\PayPalHttpClient;
    use includes\classes\PayPalCheckoutSdk\Core\ProductionEnvironment;
    use includes\classes\PayPalCheckoutSdk\Core\SandboxEnvironment;
    use includes\classes\PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
    use includes\classes\models\Order;
    use includes\classes\models\Cart;
    use Omnipay\Omnipay;

    $order = new Order;
    $cart = new Cart;
	$response = array();

    if (!isset($_SESSION['order_data']) || empty($_SESSION['order_data']))
    {
        echo json_encode(['redirect' => 'page-home.php']);
        die();
    }

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-confirmation.htm");

    $order_data = $_SESSION['order_data'];

	$PHPTemplateLayer->assignGlobal("SETTING_CONFIRMATION",isset($order_data['memberships']) && !empty($order_data['memberships']) ? SETTING_MEMBERSHIP_CONFIRMATION : SETTING_CONFIRMATION);
	$PHPTemplateLayer->assignGlobal("SETTING_CODE_CONFIRMATION",SETTING_CODE_CONFIRMATION);

    if ($uloggedin && !isset($order_data['orderPayments']) && $cart->total == 0 && $cart->itemCount === 1 && $cart->items[0]->item->membershipEventDiscountedItem) {
        if ($order->create($order_data)) {
            $cart->expire();
            unset($_SESSION['order_data']['card'], $_SESSION['order_data']['password']);
            unset($_SESSION['memberships_data'], $_SESSION['paypal_order_id']);

            $PHPTemplateLayer->assignGlobal("reference", $_SESSION['order_reference']);

            $response["completed"] = true;
        } else {
            $response["error"] = SETTING_GENERIC_ERROR_MSG;
        }

        $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
        echo json_encode($response);
        die();
    }

    if ($order_data['orderPayments'][0]['paymentMethodId'] == 3 && isset($_SESSION['paypal_order_id'])) {
        $request = new OrdersCaptureRequest($_SESSION['paypal_order_id']);
        $request->prefer('return=representation');

        try {
            $env = PAYPAL_ENVIRONMENT == 'production' ? new ProductionEnvironment(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET) : new SandboxEnvironment(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET);
            $client = new PayPalHttpClient($env);
            $paypalResponse = $client->execute($request);

            if ($paypalResponse->statusCode === 201) {
                if (isset($order_data['password']) && !empty($order_data['password'])) {
                    $customerdata = [
                        "email"             => $order_data['email'],
                        "title"             => $order_data['title'],
                        "firstName"         => $order_data['firstName'],
                        "lastName"          => $order_data['lastName'],
                        "companyName"       => $order_data['companyName'],
                        "customerIsCompany" => $order_data['customerIsCompany'],
                        "phone"             => $order_data['phone'],
                        "addressLine1"      => $order_data['addressLine1'],
                        "addressLine2"      => $order_data['addressLine2'],
                        "addressLine3"      => $order_data['addressLine3'],
                        "postcode"          => $order_data['postcode'],
                        "country"           => $order_data['country'],
                        "marketingAllowed"  => $order_data['marketingAllowed'],
                        "password"          => $order_data['password'],
                    ];

                    $customer = Customer::register($customerdata);

                    if ($customer) {
                        Customer::loginLocal($customer);

                        $order_data['customerId'] = $customer->id;
                    }
                }

                if ($order->create($order_data)) {
                    $cart->expire();
                    unset($_SESSION['memberships_data'], $_SESSION['paypal_order_id']);

                    $PHPTemplateLayer->assignGlobal("reference", $_SESSION['order_reference']);

                    $response["completed"] = true;
                } else {
                    $response["error"] = SETTING_GENERIC_ERROR_MSG;
                    // TODO: log/show error - payment taken, but order not created
                }
            }
        } catch (includes\classes\PayPalHttp\HttpException $ex) {
            $failedResponse = json_decode($ex->getMessage());

            if ($failedResponse->details[0]->issue == 'ORDER_ALREADY_CAPTURED') {
                $response["redirect"] = "page-account-orders.php";
            } else {
                $response["error"] = SETTING_GENERIC_ERROR_MSG;
                // TODO: log/show error - no payment taken
            }
        }
    } else if ($order_data['orderPayments'][0]['paymentMethodId'] == 1) {
        if (isset($_SESSION['3dSecure']) && $_SESSION['3dSecure']) {
            $gateway = OmniPay::create('SagePay\Direct')->initialize([
                'vendor'   => OPAYO_VENDORNAME,
                'testMode' => OPAYO_TESTMODE,
            ]);

            $completeRequest = $gateway->completeAuthorize([
                'transactionId' => $_SESSION['order_reference'],
            ]);

            $completeResponse = $completeRequest->send();
        }

        if (isset($order_data['password']) && !empty($order_data['password'])) {
            $customerdata = [
                "email"             => $order_data['email'],
                "title"             => $order_data['title'],
                "firstName"         => $order_data['firstName'],
                "lastName"          => $order_data['lastName'],
                "companyName"       => $order_data['companyName'],
                "customerIsCompany" => $order_data['customerIsCompany'],
                "phone"             => $order_data['phone'],
                "addressLine1"      => $order_data['addressLine1'],
                "addressLine2"      => $order_data['addressLine2'],
                "addressLine3"      => $order_data['addressLine3'],
                "postcode"          => $order_data['postcode'],
                "country"           => $order_data['country'],
                "marketingAllowed"  => $order_data['marketingAllowed'],
                "password"          => $order_data['password'],
            ];

            $customer = Customer::register($customerdata);

            if ($customer) {
                Customer::loginLocal($customer);

                $order_data['customerId'] = $customer->id;
            }
        }

        if ($order->create($order_data)) {
            $cart->expire();
            unset($_SESSION['memberships_data'], $_SESSION['paypal_order_id']);

            $PHPTemplateLayer->assignGlobal("reference", $_SESSION['order_reference']);

            $response["completed"] = true;
        } else {
            $response["error"] = SETTING_GENERIC_ERROR_MSG;
            // TODO: log/show error - payment taken, but order not created
        }

        unset($_SESSION['3dSecure']);
        $response["completed"] = true;
    } else {
        $response["error"] = SETTING_GENERIC_ERROR_MSG;
    }

    $_SESSION['order_completed'] = true;
    $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
    echo json_encode($response);