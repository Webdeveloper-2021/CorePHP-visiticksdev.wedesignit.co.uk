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
use includes\classes\models\Cart;
use includes\classes\models\Order;
use Omnipay\Common\Helper;

$cart = new Cart;
$order = new Order;

if ($cart->itemCount < 1)
{
    echo json_encode(['redirect' => 'page-cart.php']);
    die();
}

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

$usertype = $_POST['usertype'];
$title = $_POST['title'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$company = $_POST['company'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$marketing = $_POST['marketing'];
$lookup_first_line = $_POST['lookup_first_line'];
$lookup_second_line = $_POST['lookup_second_line'];
$lookup_post_town = $_POST['lookup_post_town'];
$lookup_postcode = $_POST['lookup_postcode'];
$country = $_POST['country'];
$payment_option = $_POST['paymentoption'];
$account_option = $_POST['accountoption'];
$card_number = $_POST['cardnumber'];
$expiry_month = $_POST['expirymonth'];
$expiry_year = $_POST['expiryyear'];
$card_name = $_POST['cardname'];
$card_cvv = $_POST['cardcvv'];
$password = $_POST['password'];
$password_repeat = $_POST['passwordrepeat'];

if ($uloggedin) {
    $usertype = customer_primary_contact()->companyName ? 'COMPANY' : 'INDIVIDUAL';
}

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;

$response = array(
    "completed" => "",
    "error" => ""
);

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

if($usertype == "" || !form_valid_string('VARCHAR',$usertype))
{
    $error = true;
    $response["usertype"] = "error";
}

if(SETTING_CAPTURETITLESWITHNAMES && ($usertype == 'INDIVIDUAL' || ($usertype == 'COMPANY' && SETTING_NAMEREQUIRED)))
{
    if ($title == "" || !form_valid_string('VARCHAR', $title))
    {
        $error = true;
        $response["title"] = "error";
    }
}

if($usertype == 'INDIVIDUAL' || ($usertype == 'COMPANY' && SETTING_NAMEREQUIRED)) {
    if ($firstname == "" || !form_valid_string('VARCHAR', $firstname)) {
        $error = true;
        $response["firstname"] = "error";
    }

    if ($lastname == "" || !form_valid_string('VARCHAR', $lastname)) {
        $error = true;
        $response["lastname"] = "error";
    }
}

if($usertype == 'COMPANY') {
    if ($company == "" || !form_valid_string('VARCHAR', $company)) {
        $error = true;
        $response["company"] = "error";
    }
}

if($phone == "" || !form_valid_string('VARCHAR',$phone))
{
    $error = true;
    $response["phone"] = "error";
}

if($email == "" || !form_valid_email($email))
{
    $error = true;
    $response["email"] = "error";
}

if($cart->total > 0 && ($payment_option == "" || !form_valid_string('VARCHAR',$payment_option)))
{
    $error = true;
    $response["paymentoption"] = "error";
}

if ($payment_option == 1) {
    if($lookup_first_line == "" || !form_valid_string('VARCHAR',$lookup_first_line))
    {
        $error = true;
        $response["postcode"] = "error";
    }

    if($lookup_second_line == "" || !form_valid_string('VARCHAR',$lookup_second_line))
    {
        $error = true;
        $response["postcode"] = "error";
    }

    if($lookup_post_town == "" || !form_valid_string('VARCHAR',$lookup_post_town))
    {
        $error = true;
        $response["postcode"] = "error";
    }

    if($lookup_postcode == "" || !form_valid_string('VARCHAR',$lookup_postcode))
    {
        $error = true;
        $response["postcode"] = "error";
    }

    if($card_number == "" || !form_valid_string('',$card_number, 13, 16) || !Helper::validateLuhn($card_number))
    {
        $error = true;
        $response["cardnumber"] = "error";
    }

    if($card_name == "" || !form_valid_string('VARCHAR',$card_name))
    {
        $error = true;
        $response["cardname"] = "error";
    }

    if($expiry_month == "" || !form_valid_string('',$expiry_month, 2, 2) || $expiry_month < 1 || $expiry_month > 12)
    {
        $error = true;
        $response["expirymonth"] = "error";
    }

    if($expiry_year == "" || !form_valid_string('',$expiry_year, 4, 4) || $expiry_year < date('Y') || $expiry_year > date('Y', strtotime('+20 years')) )
    {
        $error = true;
        $response["expiryyear"] = "error";
    }

    if($card_cvv == "" || !form_valid_string('',$card_cvv, 3, 4) || $card_cvv < 0)
    {
        $error = true;
        $response["cardcvv"] = "error";
    }
}

if (!$uloggedin) {
    if ($account_option == "" || !form_valid_string('VARCHAR', $account_option)) {
        $error = true;
        $response["accountoption"] = "error";
    }
}

if (($cart->hasMemberships() && !$uloggedin) || $account_option == 'CREATEACCOUNT') {
    if($password == "" || !form_valid_string('PASSWORD',$password))
    {
        $error = true;
        $response["password"] = "error";
    }

    if($password_repeat == "" || !form_valid_string('PASSWORD',$password_repeat))
    {
        $error = true;
        $response["passwordrepeat"] = "error";
    }

    if($password !== $password_repeat)
    {
        $error = true;
        $response["password"] = "error";
        $response["passwordrepeat"] = "error";
    }
}

if($lookup_first_line == "" || !form_valid_string('VARCHAR',$lookup_first_line))
{
    $error = true;
    $response["lookup_first_line"] = "error";
}

if($lookup_post_town == "" || !form_valid_string('VARCHAR',$lookup_post_town))
{
    $error = true;
    $response["lookup_post_town"] = "error";
}

if($lookup_postcode == "" || !form_valid_string('VARCHAR',$lookup_postcode))
{
    $error = true;
    $response["lookup_postcode"] = "error";
}

if($country == "" || !form_valid_string('VARCHAR',$country))
{
    $error = true;
    $response["country"] = "error";
}

if(!$error)
{
    if ($account_option == "CREATEACCOUNT" && getCustomerByEmail($email)) {
        $response["error"] = "This email is already registered.";
    } else {
        $firstname = form_process_makesafe($firstname);
        $lastname = form_process_makesafe($lastname);
        $company = form_process_makesafe($company);
        $phone = form_process_makesafe($phone);
        $email = form_process_makesafe($email);
        $lookup_first_line = form_process_makesafe($lookup_first_line);
        $lookup_second_line = form_process_makesafe($lookup_second_line);
        $lookup_post_town = form_process_makesafe($lookup_post_town);
        $lookup_postcode = form_process_makesafe($lookup_postcode);
        $country = form_process_makesafe($country);
        $payment_option = form_process_makesafe($payment_option);

        if (SETTING_CAPTURETITLESWITHNAMES) {
            $title = form_process_makesafe($title);
        }

        if (SETTING_MARKETINGALLOWED) {
            $marketing = form_process_makesafe($marketing);
            $marketing = process_bolean_checkbox($marketing);
        }

        $order_data = [
            "basketId"          => $cart->id,
            "email"             => $email,
            "title"             => $title,
            "firstName"         => $firstname,
            "lastName"          => $lastname,
            "companyName"       => $company,
            "customerIsCompany" => $usertype == "COMPANY",
            "phone"             => $phone,
            "addressLine1"      => $lookup_first_line,
            "addressLine2"      => $lookup_second_line,
            "addressLine3"      => $lookup_post_town,
            "postcode"          => $lookup_postcode,
            "country"           => $country,
            "marketingAllowed"  => $marketing
        ];

        if ($uloggedin) {
            $order_data["customerId"] = $U_SESSION_CUSTOMER_ID;
        }

        if ($payment_option) {
            $payment_name = 'Paypal';

            if ($payment_option == 1)
                $payment_name = getCardBrand($card_number);

            if ($payment_option == 2)
                $payment_name = 'Apple Pay';

            $order_data["orderPayments"] = [
                [
                    "paymentMethodId" => intval($payment_option),
                    "amount"          => $cart->total,
                    "name"            => $payment_name
                ]
            ];
        }

        if ($payment_option == 1) {
            $order_data["orderPayments"][0]["cardNumber"] = substr($card_number, -4);
            $order_data["orderPayments"][0]["expiryDate"] = $expiry_month . "/" . $expiry_year;
        }

        unset($order_data["card"]);

        if ($cart->hasMemberships()) {
            if ($payment_option == 1) {
                $order_data["card"] = [
                    'name'         => $card_name,
                    'number'       => $card_number,
                    'expiry_month' => $expiry_month,
                    'expiry_year'  => $expiry_year,
                    'cvv'          => $card_cvv
                ];
            }

            $order_data['password'] = $password;
            $_SESSION['order_data'] = $order_data;

            $response["completed"] = true;
            $response["url"] = "#checkout-memberships";
        } else {
            if ($order->create($order_data, true)) {
                if ($payment_option == 1) {
                    $order_data["card"] = [
                        'name'         => $card_name,
                        'number'       => $card_number,
                        'expiry_month' => $expiry_month,
                        'expiry_year'  => $expiry_year,
                        'cvv'          => $card_cvv
                    ];
                }

                $order_data['password'] = $password;
                $_SESSION['order_data'] = $order_data;

                $response["completed"] = true;
                $response["url"] = "#checkout-summary";
            } else {
                $response["error"] = SETTING_GENERIC_ERROR_MSG;
            }
        }
    }
}
else
{
    $response["error"] = "Please correct the errors highlighted below:";
}

echo json_encode($response);