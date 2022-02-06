<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "110";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/customers-password.htm");

///*****************************************************************************///
///UNIVERSAL PAGE CODE: UNIVERAL VARIABLES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TITLE",SETTING_TITLE);
	$PHPTemplateLayer->assignGlobal("INSTALL_VISITICKETS",INSTALL_VISITICKETS);
	$PHPTemplateLayer->assignGlobal("U_YEAR",$U_YEAR);

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

	$success = $_GET['success'];
	$error = $_GET['error'];
	$pagenumber = $_GET['pagenumber'];
	$id = $_GET['id'];

	$PHPTemplateLayer->assignGlobal("contentid",$id);
	$PHPTemplateLayer->assignGlobal("pagenumber",$pagenumber);

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:customers.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///GET CUSTOMER DATA///
///*****************************************************************************///

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

// Get data for existing customer

	$name = "Ross McDonald";

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));

		if($_POST['submit'])
		{
		$newpassword = htmlentities($_POST['newpassword']); 
		$newrepeatpassword = htmlentities($_POST['newrepeatpassword']); 

		$PHPTemplateLayer->assignGlobal("newpassword",$newpassword);
		$PHPTemplateLayer->assignGlobal("newrepeatpassword",$newrepeatpassword);

		$newpassword = form_process_makesafe($newpassword);
		$newrepeatpassword = form_process_makesafe($newrepeatpassword);

			if(!$newpassword || !form_valid_string('PASSWORD',$password))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$newrepeatpassword || $newrepeatpassword != $newpassword)
			{
			$error = "1";
			$error2 = "1";
			}

			if(!$error)
			{

// UPDATE PASSWORD ACTIONS

			header("Location:customers-password.php?success=1");
			exit();
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("newpasswordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("newpasswordclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("newrepeatpasswordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("newrepeatpasswordclass",'textbox-noerror');
		}

///*****************************************************************************///
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

		if($error)
		{
		$PHPTemplateLayer->block("ERROR");

			if($apierror)
			{
			$error = ShowAPIError($apierror);
			}
			else
			{
			$error = "Please correct the errors displayed below.";
			}

		$PHPTemplateLayer->assign("error",$error);

			if($error1 == "1")
			{
			$error1 = 'Please enter a valid password';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Your new password and repeated password do not match';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);
		}
		elseif($success)
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'The customer\'s password has been updated.';
			}

		$PHPTemplateLayer->assignGlobal("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>