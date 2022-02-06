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
	$password = $_POST['password'];
	$redirect = $_POST['redirect'];

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

		if($email == "" || !form_valid_email($email))
		{
		$error = true;
		$response["email"] = "error";
		}

		if($password == "")
		{
		$error = true;
		$response["password"] = "error";
		}

		if(!$error) {
            $password = form_process_makesafe($password);
            $email = form_process_makesafe($email);

            $requestresult = API::post("customers/login?include=CustomerAddresses.Address,CustomerContacts.Contact", [
                'Password' => $password,
                'Email' => $email
            ]);

            if (!$requestresult['ok']) {
                $errormessage = SETTING_GENERIC_ERROR_MSG;

                foreach ($requestresult['content'] as $requestresultkey => $requestresultobject) {
                    if ($requestresultobject->errorNumber == "45") {
                        $errormessage = "Your email address or password was not recognised.<br/>";
                        $response["email"] = "error";
                        $response["password"] = "error";
                    }
                }

                $response["error"] = $errormessage;
            } else {
                process_member_login($requestresult['content'], $email);

                if ($redirect) {
                    $response["redirect"] = $redirect;
                } else {
                    $response["completed"] = true;
                }
            }
        }
		else
		{
		$response["error"] = "Please correct the errors highlighted below:";
		}

	echo json_encode($response);