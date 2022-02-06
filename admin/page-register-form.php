<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

use includes\classes\models\Customer;

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES///
///*****************************************************************************///

	require("includes/visitickets.php");

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

    $usertype = $_POST['usertype'];
	$title = $_POST['title'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
    $company = $_POST['company'];
	$telephone = $_POST['telephone'];
	$email = $_POST['email'];
	$marketing = $_POST['marketing'];
	$password = $_POST['password'];
	$repeatpassword = $_POST['repeatpassword'];
	$lookup_first_line = $_POST['lookup_first_line'];
	$lookup_second_line = $_POST['lookup_second_line'];
	$lookup_post_town = $_POST['lookup_post_town'];
	$lookup_postcode = $_POST['lookup_postcode'];
	$country = $_POST['country'];
	$company = $_POST['company'];
    $redirect = $_POST['redirect'];

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
///
        if($usertype == "" || !form_valid_string('VARCHAR',$usertype))
        {
            $error = true;
            $response["usertype"] = "error";
        }

        if(SETTING_CAPTURETITLESWITHNAMES && ($usertype == 'INDIVIDUAL' || ($usertype == 'COMPANY' && SETTING_NAMEREQUIRED))) {
            if ($title == "" && !form_valid_string('', $title, "1", "50")) {
                $error = true;
                $response["title"] = "error";
            }
        }

        if($usertype == 'INDIVIDUAL' || ($usertype == 'COMPANY' && SETTING_NAMEREQUIRED)) {
            if ($firstname == "" || !form_valid_string('', $firstname, "1", "200")) {
                $error = true;
                $response["firstname"] = "error";
            }

            if ($lastname == "" || !form_valid_string('', $lastname, "1", "200")) {
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

		if($telephone == "" || !form_valid_string('',$telephone,"1","100"))
		{
		$error = true;
		$response["telephone"] = "error";
		}

		if($email == "" || !form_valid_email($email))
		{
		$error = true;
		$response["email"] = "error";
		}

		if($lookup_postcode == "")
		{
		$error = true;
		$response["postcode-lookup-input"] = "error";
		$response["postcode-lookup-button"] = "error";
		}

		if($password == "" || !form_valid_string('PASSWORD',$password))
		{
		$error = true;
		$response["password"] = "error";
		}

		if($repeatpassword == "" || $repeatpassword != $password)
		{
		$error = true;
		$response["repeatpassword"] = "error";
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
		$password = form_process_makesafe($password);
		$firstname = form_process_makesafe($firstname);
		$lastname = form_process_makesafe($lastname);
		$email = form_process_makesafe($email);
		$telephone = form_process_makesafe($telephone);
		$lookup_first_line = form_process_makesafe($lookup_first_line);
		$lookup_second_line = form_process_makesafe($lookup_second_line);
		$lookup_post_town = form_process_makesafe($lookup_post_town);
		$lookup_postcode = form_process_makesafe($lookup_postcode);
		$country = form_process_makesafe($country);

			if(SETTING_CAPTURETITLESWITHNAMES)
            {
			$title = form_process_makesafe($title);
			}

			if(SETTING_MARKETINGALLOWED)
			{
			$marketing = form_process_makesafe($marketing);
			$marketing = process_bolean_checkbox($marketing);
			}


            $data = [
                'password'          => $password,
                'firstName'         => $firstname,
                'lastName'          => $lastname,
                'email'             => $email,
                'phone'             => $telephone,
                'title'             => $title,
                'addressLine1'      => $lookup_first_line,
                'addressLine2'      => $lookup_second_line,
                'addressLine3'      => $lookup_post_town,
                'postcode'          => $lookup_postcode,
                'country'           => $country,
                'marketingAllowed'  => $marketing,
                'customerIsCompany' => $usertype == 'COMPANY',
                "companyName"       => $company
            ];

            $customer = Customer::register($data);

            if ($customer['status']) {
                Customer::loginLocal($customer['content']);

                if ($redirect)
                {
                    $response["redirect"] = $redirect;
                }
                else
                {
                    $response["completed"] = true;
                }
            } else {
                if($customer['errorNumber'] == 35)
                {
                    $response["error"] = "The email address you entered is already registered.";
                    $response["email"] = "error";
                }
            }
		}
		else
		{
		$response["error"] = "Please correct the errors highlighted below:";
		}

	echo json_encode($response);