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
	$oldpassword = $_POST['oldpassword'];

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
        $errormessage = "";

        if($password == "" || $passwordrepeat == "" || $oldpassword == "") {
            $errormessage .= "Please fill in all fields.<br>";
        }

		if($password == "" || !form_valid_string('PASSWORD',$password))
		{
		$error = true;
		$response["password"] = "error";
        $errormessage .= "Your new password does not contain the required combination of characters.<br/>";
		}

		if($passwordrepeat == "" || $passwordrepeat != $password)
		{
		$error = true;
		$response["passwordrepeat"] = "error";
        $errormessage .= "Your new password and repeated password do not match.<br/>";
		}

		if($oldpassword == "")
		{
		$error = true;
		$response["oldpassword"] = "error";
		}

		if(!$error)
		{
		$oldpassword = form_process_makesafe($oldpassword);
		$password = form_process_makesafe($password);
		$email = form_process_makesafe($U_SESSION_CUSTOMER_EMAIL);

		$requestresult = API::post("customers/changepassword", [
            'email' => $email,
            'currentPassword' => $oldpassword,
            'newPassword' => $password
        ]);

			if(!$requestresult['ok'])
			{
            $errormessage = "";

                foreach($requestresult as $requestresultkey => $requestresultobject)
                {
                    if($requestresultobject->errorNumber == "901")
                    {
                    $errormessage .= "Your old password is not correct.<br/>";
                    $response["oldpassword"] = "error";
                    }
                }

            $response["error"] = $errormessage;
            }
            else
            {
            $response["completed"] = "true";
            }
		}
		else
		{
		$response["error"] = $errormessage;
		}

	echo json_encode($response);