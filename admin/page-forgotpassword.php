<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///

	header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-forgotpassword.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if($uloggedin)
		{
		$response["redirect"] = "page-account.php";
		}
		else
		{
		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);