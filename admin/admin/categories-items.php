<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "40";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	use includes\classes\controllers\CategoryController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/categories-items.htm");

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
///GET CATEGORY DATA///
///*****************************************************************************///

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

// Get data for existing category

	$category = new CategoryController();
	$category = $category->view($id);

	$name = $category->name;

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));

///*****************************************************************************///



	$requesturl = "catalog/".$id;
	$requesttype = "GET";
	$requestbody = "";

	$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

	if($requestresult != "ERROR")
	{
	    $requestresultarray = explode('|X|',$requestresult);
	    $requestresult = json_decode($requestresultarray[1]);
// echo '<pre>';echo print_r($requestresult);echo '</pre>';exit;
	    if($requestresultarray[0] != "FAIL")
	    {
			// $pagenumber = $_GET['pagenumber'];
			
			// 	if(!$pagenumber)
			// 	{
			// 	$pagenumber = "0";
			// 	}
			// 	else
			// 	{
			// 	$pagenumber = $pagenumber-1;
			// 	}

			$totalresults = count($requestresult);

			// $itemviewtext = ItemViewText($totalresults,$pagenumber,ADMIN_PERPAGE);

			// $PHPTemplateLayer->assignGlobal("itemviewtext",$itemviewtext);
			$PHPTemplateLayer->assignGlobal("totalresults",$totalresults - 1);

	        if ($totalresults)
			{
			$PHPTemplateLayer->block("CONTENTTABLE");

			usort($requestresult, function($a, $b) {
				return strcmp($a->displayOrder, $b->displayOrder);
			});

			$PHPTemplateLayer->assignGlobal("topCategoryDisplayOrder",$requestresult[0]->displayOrder);
			$PHPTemplateLayer->assignGlobal("lastCategoryDisplayOrder",$requestresult[$totalresults - 1]->displayOrder);

			$itemcount = 0;

				foreach($requestresult as $itemkey => $itemobject)
				{
				$PHPTemplateLayer->block("CONTENTROW");

				$itemcount = $itemcount+1;

				$itemid = $itemobject->id;
				$itemtitle = $itemobject->name;
				$itemdescription = $itemobject->description;
				$itemimage = $itemobject->imageFileName;
				$categorydisplayOrder = $itemobject->displayOrder;

				$itempicture = DefaultImage($itemimage,SETTING_DEFAULTIMAGE);

				$PHPTemplateLayer->assign("categoryid",$id);
				$PHPTemplateLayer->assign("contentid",$itemid);
				$PHPTemplateLayer->assign("contentimage",$itempicture);
				$PHPTemplateLayer->assign("contenttitle",$itemtitle);
				$PHPTemplateLayer->assign("contentevent",$itemobject->event->name);
				$PHPTemplateLayer->assign("contentorder",$categorydisplayOrder);
				}
			}
			else
			{
			$PHPTemplateLayer->block("NOCONTENTTABLE");
			}
	    }
	}



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
			$success = 'You have successfully updated the order of items in this category';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>