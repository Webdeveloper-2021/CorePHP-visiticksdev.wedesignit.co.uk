<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

	header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-account.htm");
    $PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_PLURAL",SETTING_PASSTITLE_PLURAL);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

	$successid = $_POST['successid'] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
			if($successid)
			{
			$PHPTemplateLayer->block("SHOWSUCCESS");

            $successmessage = "Confirmation: You have successfully registered and have been logged in.";

				if($successid == "2")
				{
				$successmessage = "Confirmation: You have successfully logged in.";
				}

			$PHPTemplateLayer->assign("successmessage",$successmessage);
			}

            $response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);
