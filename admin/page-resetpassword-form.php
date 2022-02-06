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

$password = $_POST['password'];
$passwordrepeat = $_POST['passwordrepeat'];
$token = $_POST['_token'];
$email = $_POST['_email'];

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;

$response = array(
    "completed" => "",
    "error"     => ""
);

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

if($password == "" || !form_valid_string('PASSWORD',$password))
{
    $error = true;
    $response["password"] = "error";
}

if($passwordrepeat == "" || $passwordrepeat != $password)
{
    $error = true;
    $response["passwordrepeat"] = "error";
}

if(!$error)
{
    $email = form_process_makesafe($email);

    $requestresult = API::post("customers/resetpassword", [
        'email' => $email,
        'token' => $token,
        'newpassword' => $password
    ]);

    if(!$requestresult['ok'])
    {
        $errormessage = SETTING_GENERIC_ERROR_MSG;

        foreach($requestresult['content'] as $requestresultkey => $requestresultobject)
        {
            if($requestresultobject->errorNumber == "902")
            {
                $errormessage .= "Invalid token.<br/>";
            }
        }

        $response["error"] = $errormessage;
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