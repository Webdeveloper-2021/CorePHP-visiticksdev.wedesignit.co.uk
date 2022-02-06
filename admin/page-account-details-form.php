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

	$company = $_POST['companyname'];
	$title = $_POST['title'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$phone = $_POST['phone'];
    $marketing = $_POST['marketing'];
    $lookup_first_line = $_POST['lookup_first_line'];
    $lookup_second_line = $_POST['lookup_second_line'];
    $lookup_post_town = $_POST['lookup_post_town'];
    $lookup_postcode = $_POST['lookup_postcode'];
    $country = $_POST['country'];

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

	$error = false;

	$response = array("completed" => "");

///*****************************************************************************///
/////PAGE SPECIFIC FUNCTIONS///
/////*****************************************************************************///

    function updateAddress($firstline, $secondline, $posttown, $postcode, $country) {
        $requestresult = API::put("addresses/" . customer_primary_address()->id, [
            'addressLine1' => $firstline,
            'addressLine2' => $secondline,
            'addressLine3' => $posttown,
            'postcode' => $postcode,
            'country' => $country
        ]);

        if($requestresult['ok'])
        {
            // update session
            update_customer_address(customer_primary_contact()->id, '', $firstline, $secondline, $posttown, $postcode, $country);

            return true;
        }

        return false;
    }

    function updateContact($title, $firstname, $lastname, $phone, $marketing, $company = '') {
        $requestresult = API::put("contacts/" . customer_primary_contact()->id, [
            'title' => $title,
            'firstName' => $firstname,
            'lastName' => $lastname,
            'phone' => $phone,
            'marketingAllowed' => $marketing,
            'companyName' => $company
        ]);

        if($requestresult['ok'])
        {
            // update session
            update_customer_contact(customer_primary_contact()->id, $title, $firstname, $lastname, $phone, $marketing, $company);

            return true;
        }

        return false;
    }

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

        $isCompany = customer_primary_contact()->companyName ?? false;

        if(SETTING_CAPTURETITLESWITHNAMES && (!$isCompany || ($isCompany && SETTING_NAMEREQUIRED)))
        {
            if ($title == "" || !form_valid_string('VARCHAR', $title))
            {
            $error = true;
            $response["title"] = "error";
            }
        }

        if(!$isCompany || ($isCompany && SETTING_NAMEREQUIRED)) {
            if ($firstname == "" || !form_valid_string('VARCHAR', $firstname)) {
                $error = true;
                $response["firstname"] = "error";
            }

            if ($lastname == "" || !form_valid_string('VARCHAR', $lastname)) {
                $error = true;
                $response["lastname"] = "error";
            }
        }

        if ($isCompany) {
            if($company == "" || !form_valid_string('VARCHAR',$company))
            {
                $error = true;
                $response["company"] = "error";
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

		if($phone == "" || !form_valid_string('VARCHAR',$phone))
		{
		$error = true;
		$response["phone"] = "error";
		}

		if(!$error)
		{
            $firstname = form_process_makesafe($firstname);
            $lastname = form_process_makesafe($lastname);
            $phone = form_process_makesafe($phone);
            $lookup_first_line = form_process_makesafe($lookup_first_line);
            $lookup_second_line = form_process_makesafe($lookup_second_line);
            $lookup_post_town = form_process_makesafe($lookup_post_town);
            $lookup_postcode = form_process_makesafe($lookup_postcode);
            $country = form_process_makesafe($country);

            if ($isCompany) {
                $company = form_process_makesafe($company);
            }

            if(SETTING_CAPTURETITLESWITHNAMES)
            {
                $title = form_process_makesafe($title);
            }

            if(SETTING_MARKETINGALLOWED)
            {
                $marketing = form_process_makesafe($marketing);
                $marketing = process_bolean_checkbox($marketing);
            }

            if (!updateAddress($lookup_first_line,$lookup_second_line,$lookup_post_town,$lookup_postcode,$country) || !updateContact($title,$firstname,$lastname,$phone,$marketing,$company)) {
                $response["error"] = SETTING_GENERIC_ERROR_MSG;
            } else {
                $response["completed"] = true;
            }
        }
        else
        {
            $response["error"] = "Please correct the errors highlighted below:";
		}

	echo json_encode($response);