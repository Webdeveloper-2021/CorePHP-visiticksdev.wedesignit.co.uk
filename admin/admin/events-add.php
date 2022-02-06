<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "20";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/events-add.htm");

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
	$tab = $_GET['tab'];

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:events.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///CATEGORIES///
///*****************************************************************************///

	$categoryrequesturl = "categories";
	$categoryrequesttype = "GET";
	$categoryrequestbody = "";

	$categoryrequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$categoryrequesturl,$categoryrequesttype,$categoryrequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($categoryrequestresult != "ERROR")
		{
		$categoryrequestresultarray = explode('|X|',$categoryrequestresult);
		$categoryrequestresult = json_decode($categoryrequestresultarray[1]);

			if($categoryrequestresultarray[0] != "FAIL")
			{
			$categorytotalresults = count($categoryrequestresult);

				if($categorytotalresults)
				{
				$PHPTemplateLayer->block("CATEGORIES");

				$categoryIds = array();

					foreach($categoryrequestresult as $categorykey => $categoryobject)
					{
					$PHPTemplateLayer->block("CATEGORY");

					$categorytitle = $categoryobject->name;
					$categoryid = $categoryobject->id;

						if($_POST["categoryIds".$categoryid] == "1")
						{
						$categoryselected = 'checked="checked"';

						$categoryIds[] = $categoryid;
						}
						else
						{
						$categoryselected = '';
						}

					$PHPTemplateLayer->assign("categoryid",$categoryid);
					$PHPTemplateLayer->assign("categorytitle",$categorytitle);
					$PHPTemplateLayer->assign("categoryselected",$categoryselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOCATEGORIES");

				$categoryIds = "";
				}
			}
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///



///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///


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