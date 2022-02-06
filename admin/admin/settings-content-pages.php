<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "160";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-content-pages.htm");

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
	$apierror = $_GET['apierror'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORIES///
///*****************************************************************************///

	$requestresult = json_decode(ADMIN_PAGECONTENT);

	$totalresults = count($requestresult);

	$itemviewtext = ItemViewText($totalresults,'0','100000');

	$PHPTemplateLayer->assignGlobal("itemviewtext",$itemviewtext);
	$PHPTemplateLayer->assignGlobal("paging",$paging);
	$PHPTemplateLayer->assignGlobal("pagenumber",$pagenumber);

		if($totalresults)
		{
		$PHPTemplateLayer->block("CONTENTTABLE");

		usort($requestresult, 'process_content_displayOrder');

			foreach($requestresult as $categorykey => $categoryobject)
			{
			$PHPTemplateLayer->block("CONTENTROW");

			$pageid = $categoryobject->id;
			$pagetitle = $categoryobject->name;

			$PHPTemplateLayer->assign("contentid",$pageid);
			$PHPTemplateLayer->assign("contenttitle",$pagetitle);
			}
		}
		else
		{
		$PHPTemplateLayer->block("NOCONTENTTABLE");
		}

///*****************************************************************************///
///PAGE MESSAGES///
///*****************************************************************************///

		if($success != "")
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'You have successfully added a new item';
			}
			elseif($success == "2")
			{
			$success = 'You have successfully edited an item';
			}
			elseif($success == "3")
			{
			$success = 'You have successfully deleted an item';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

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