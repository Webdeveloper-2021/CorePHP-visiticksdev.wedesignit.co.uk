<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "ACCOUNT";
	$adminpermission = "";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/index.htm");

///*****************************************************************************///
///UNIVERSAL PAGE CODE: UNIVERAL VARIABLES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TITLE",SETTING_TITLE);
	$PHPTemplateLayer->assignGlobal("INSTALL_VISITICKETS",INSTALL_VISITICKETS);
	$PHPTemplateLayer->assignGlobal("U_YEAR",$U_YEAR);

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

	$error = $_GET['error'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: ERROR MESSAGES///
///*****************************************************************************///

		if($error)
		{
		$PHPTemplateLayer->block("ERROR");

			if($error == "unexpected")
			{
			$error = "An unexpected error has occurred.";
			}
			elseif($error == "nopermission")
			{
			$error = "You do not have permission to view the page you were trying to access.";
			}

		$PHPTemplateLayer->assign("error",$error);
		}

///*****************************************************************************///
///UNIVERSAL PAGE CODE: OUTPUT TEMPLATED HTML///
///*****************************************************************************///

	$PHPTemplateLayer->display('','','MINIFY');
?>