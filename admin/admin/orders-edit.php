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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/orders-edit.htm");

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
		header("Location:orders.php?pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search");
		exit();
		}

///*****************************************************************************///
///PROCESS EMAIL RESEND///
///*****************************************************************************///

		if($_POST['resendemail'])
		{
		header("Location:orders-resend.php?id=$id&pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search");
		exit();
		}

///*****************************************************************************///
///PROCESS ORDER CANCEL///
///*****************************************************************************///

		if($_POST['cancelorder'])
		{
		header("Location:orders-cancel.php?id=$id&pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search");
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

// Get data for existing order

	$orderid = "XYZ";
	$orderdate = "";
	$ordertotal = "175.00";
	$customerid = "";
	$customername = "Ross McDonald";
	$orderposSale = 1;
	$giftAid = 1;
	$paymenttype = "1";
	$paymentcardNumber = "0001";
	$paymentexpiryDate = "10/2025";
	$paymentname = "VISA";

		if($orderposSale)
		{
		$ordersource = "POS";
		}
		else
		{
		$ordersource = "Online";
		}

		if($giftAid)
		{
		$ordergiftaid = "Yes";
		}
		else
		{
		$ordergiftaid = "No";
		}

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($orderid));
	$PHPTemplateLayer->assignGlobal("orderdate",htmlspecialchars($orderdate));
	$PHPTemplateLayer->assignGlobal("ordertotal",htmlspecialchars($ordertotal));
	$PHPTemplateLayer->assignGlobal("customerid",htmlspecialchars($customerid));
	$PHPTemplateLayer->assignGlobal("customername",htmlspecialchars($customername));
	$PHPTemplateLayer->assignGlobal("ordersource",htmlspecialchars($ordersource));
	$PHPTemplateLayer->assignGlobal("ordergiftaid",htmlspecialchars($ordergiftaid));

		if($paymenttype == "2")
		{
		$PHPTemplateLayer->block("PAYPAL");
		}
		elseif($paymenttype == "1")
		{
		$PHPTemplateLayer->block("CREDITCARD");
		$PHPTemplateLayer->assign("paymentname",htmlspecialchars($paymentname));
		$PHPTemplateLayer->assign("paymentcardNumber",htmlspecialchars($paymentcardNumber));
		$PHPTemplateLayer->assign("paymentexpiryDate",htmlspecialchars($paymentexpiryDate));
		}

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
			$success = 'You have successfully edited this order\'s billing details.';
			}
			elseif($success == "2")
			{
			$success = 'You have successfully resent this order\'s email confirmation.';
			}
			elseif($success == "3")
			{
			$success = 'You have successfully cancelled this order.';
			}
			elseif($success == "4")
			{
			$success = 'You have successfully cancelled and refunded this order.';
			}
			elseif($success == "5")
			{
			$success = 'You have successfully cancelled a line item.';
			}
			elseif($success == "6")
			{
			$success = 'You have successfully cancelled and refunded a line item.';
			}
			elseif($success == "7")
			{
			$success = 'You have successfully added a new transaction for this order.';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>