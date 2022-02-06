<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "130";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/subscriptions-edit.htm");

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
		header("Location:subscriptions.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///GET CUSTOMER DATA///
///*****************************************************************************///

//		if(!$id)
//		{
//		header("Location:index.php?error=unexpected");
//		exit();
//		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

// Get data for existing customer

	$reference = "XYZ";
	$customerId = "2";
	$subscriptionadults = "2";
	$subscriptionchildren = "1";
	$subscriptionpeople = "3";
	$subscriptionexpires = "true";
	$expirationDate = "2022-03-05T00:00:00";
	$subscriptionexpired = 1;

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($reference));

	$customername = "Ross McDonald";

		if($subscriptionexpired)
		{
		$subscriptionstatus = "Expired";
		}
		else
		{
		$subscriptionstatus = "Active";
		}

		if($subscriptionexpires)
		{
		$subscriptionexpiry = $expirationDate;
		}
		else
		{
		$subscriptionexpiry = "N/A";
		}

	$PHPTemplateLayer->assignGlobal("customername",htmlspecialchars($customername));
	$PHPTemplateLayer->assignGlobal("customerid",htmlspecialchars($customerId));
	$PHPTemplateLayer->assignGlobal("subscriptionexpiry",htmlspecialchars($subscriptionexpiry));
	$PHPTemplateLayer->assignGlobal("subscriptionpeople",htmlspecialchars($subscriptionpeople));
	$PHPTemplateLayer->assignGlobal("subscriptionadults",htmlspecialchars($subscriptionadults));
	$PHPTemplateLayer->assignGlobal("subscriptionchildren",htmlspecialchars($subscriptionchildren));
	$PHPTemplateLayer->assignGlobal("subscriptionstatus",htmlspecialchars($subscriptionstatus));
	$PHPTemplateLayer->assignGlobal("subscriptionid",htmlspecialchars($id));







///*****************************************************************************///
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

		if($error || $apierror)
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


		}
		elseif($success != "")
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'You have successfully edited this item';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>