<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "80";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/customers-orders.htm");

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

///*****************************************************************************///
///GET ORDERS FOR CUSTOMER///
///*****************************************************************************///

	$requesturl = "orders?customerId=".$id."&include=BillingContact,BillingAddress,OrderLines,OrderLines.Item,OrderLines.EventSession,OrderLines.OrderLineQuantityInstances,OrderLines.OrderLineQuantityInstances.MembershipSubscription.Membership,OrderLines.OrderLineQuantityInstances.MembershipSubscription.Members,OrderLines.OrderLineQuantityInstances.MembershipSubscription.RedemptionCount,OrderLines.RedemptionCount,OrderLines.OrderLineQuantityInstances.MembershipSubscription.MembershipRedemptions.MemberRedemptions";
	$requesttype = "GET";
	$requestbody = "";

	$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($requestresult != "ERROR")
		{
		$requestresultarray = explode('|X|',$requestresult);
		$requestresult = json_decode($requestresultarray[1]);

			if($requestresultarray[0] == "SUCCESS")
			{
			$totalresults = count($requestresult);

			$itemviewtext = ItemViewText($totalresults,'0',ADMIN_PERPAGE);

			$PHPTemplateLayer->assignGlobal("itemviewtext",$itemviewtext);

				if($totalresults)
				{
				$PHPTemplateLayer->block("CONTENTTABLE");

				usort($requestresult, 'process_content_displayOrder');

				$itemcount = 0;

					foreach($requestresult as $customerkey => $customerobject)
					{
					$PHPTemplateLayer->block("CONTENTROW");

					$customerid = $customerobject->id;


					$PHPTemplateLayer->assign("contentid",$customerid);
					$PHPTemplateLayer->assign("contentname",$customername);
					$PHPTemplateLayer->assign("contentemail",$customeremail);
					$PHPTemplateLayer->assign("contentphone",$customerphone);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOCONTENTTABLE");
				}
			}
			else
			{
			$apierror = 1;
			}
		}
		else
		{
		$apierror = 1;
		}

///*****************************************************************************///
///PAGE MESSAGES///
///*****************************************************************************///

		if($error || $apierror)
		{
		$PHPTemplateLayer->block("ERROR");

			if($apierror)
			{
			$error = ShowAPIError($apierror);
			}

		$PHPTemplateLayer->assign("error",$error);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>