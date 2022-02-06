<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "120";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/customers-subscriptions.htm");

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

	$requesturl = "membershipsubscriptions/customer/".$id."?include=Membership,Members";
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

					foreach($requestresult as $subscriptionkey => $subscriptionobject)
					{
					$PHPTemplateLayer->block("CONTENTROW");

					$subscriptionid = $subscriptionobject->id;
					$subscriptionreference = $subscriptionobject->reference;
					$subscriptionexpired = $subscriptionobject->expired;

						if($subscriptionexpired)
						{
						$subscriptionstatus = "Expired";
						}
						else
						{
						$subscriptionstatus = "Active";
						}

					$PHPTemplateLayer->assign("contentid",$subscriptionid);
					$PHPTemplateLayer->assign("contentname",$subscriptionname);
					$PHPTemplateLayer->assign("contentreference",$subscriptionreference);
					$PHPTemplateLayer->assign("contentstatus",$subscriptionstatus);
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