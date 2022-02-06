<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

use includes\classes\API;

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES///
///*****************************************************************************///

require("includes/visitickets.php");

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

$email = $_POST['email'];

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;

$response = array(
    "completed" => "",
    "error"     => ""
);

///*****************************************************************************///
/////PAGE SPECIFIC FUNCTIONS///
/////*****************************************************************************///

function passwordResetToken($email) {
    global $U_SESSION_API_TOKEN, $U_DATE, $U_DATETIME;

    $email = form_process_makesafe($email);

    $requestresult = API::post("customers/generatepasswordresettoken", [
        'email' => $email
    ]);

    if($requestresult['ok'])
    {
        return $requestresult['content']->token;
    }

    return false;
}

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

if($email == "" || !form_valid_email($email) || ! getCustomerByEmail($email))
{
    $error = true;
    $response["email"] = "error";
}

if(!$error)
{
    $email = form_process_makesafe($email);
    $returnurl = base_url() . '/#resetpassword?token=' . urlencode(passwordResetToken($email)) . '&email=' . $email;

    $requestresult = API::post("customers/sendpasswordresetemail", [
        'email' => $email,
        'emailReturnUrl' => $returnurl
    ]);

    if(!$requestresult['ok'])
    {
        $response["error"] = SETTING_GENERIC_ERROR_MSG;
    }
    else
    {
        $response["completed"] = true;
    }
}
else
{
    $response["error"] = "Please correct the errors highlighted below:";
}

echo json_encode($response);