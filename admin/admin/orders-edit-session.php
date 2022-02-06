<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "90";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/orders-edit-session.htm");

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
	$bookingid = $_GET['bookingid'];
	$fromDate = $_GET['fromDate'];
	$toDate = $_GET['toDate'];
	$search = $_GET['search'];

	$PHPTemplateLayer->assignGlobal("contentid",$id);
	$PHPTemplateLayer->assignGlobal("pagenumber",$pagenumber);
	$PHPTemplateLayer->assignGlobal("search",$search);
	$PHPTemplateLayer->assignGlobal("fromDate",$fromDate);
	$PHPTemplateLayer->assignGlobal("toDate",$toDate);

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:orders-edit.php?id=$id&pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search");
		exit();
		}

///*****************************************************************************///
///GET CUSTOMER DATA///
///*****************************************************************************///

		if(!$id || !$bookingid)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

// Get data for existing order and booking

	$orderid = "XYZ";
	$eventtitle = "Clowns on Ice";
	$tickets = "Adult Ticket ×2";
	$booking = "19th July 2021 at 10:30";

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($orderid));
	$PHPTemplateLayer->assignGlobal("eventtitle",htmlspecialchars($eventtitle));
	$PHPTemplateLayer->assignGlobal("tickets",htmlspecialchars($tickets));
	$PHPTemplateLayer->assignGlobal("booking",htmlspecialchars($booking));


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

	$PHPTemplateLayer->display('','','MINIFY');
?>