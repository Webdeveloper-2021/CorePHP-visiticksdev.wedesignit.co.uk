<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "180";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-poscodes.htm");

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
	$apierror = $_GET['apierror'];
	$pagenumber = $_GET['pagenumber'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORIES///
///*****************************************************************************///

	$baseurl = "settings-configuration-poscodes.php?tab=$tab&pagenumber=";

		if(!$pagenumber)
		{
		$pagenumber = "0";
		}
		else
		{
		$pagenumber = $pagenumber-1;
		}

	$requesturl = "paymentmethods?pageNumber=".$pagenumber."&pageSize=".ADMIN_PERPAGE;
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

				if($totalresults > "0")
				{
				$nextrequesturl = "paymentmethods?pageNumber=".($pagenumber+1)."&pageSize=".ADMIN_PERPAGE;
				$nextrequesttype = "GET";
				$nextrequestbody = "";

				$nextrequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$nextrequesturl,$nextrequesttype,$nextrequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

					if($nextrequestresult != "ERROR")
					{
					$nextrequestresultarray = explode('|X|',$nextrequestresult);
					$nextrequestresult = json_decode($nextrequestresultarray[1]);

						if($nextrequestresultarray[0] == "SUCCESS")
						{
						$nextresults = count($nextrequestresult);
						}
						else
						{
						$nextresults = "0";
						}
					}
					else
					{
					$nextresults = "0";
					}

				$paging = GetPaging($totalresults,$nextresults,$pagenumber,$baseurl,ADMIN_PERPAGE);
				}
				else
				{
				$paging = "";
				}

			$itemviewtext = ItemViewText($totalresults,$pagenumber,ADMIN_PERPAGE);

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

					$poscodeid = $categoryobject->id;
					$poscodetitle = $categoryobject->name;
					$poscodeposCode = $categoryobject->posCode;

					$PHPTemplateLayer->assign("contentid",$poscodeid);
					$PHPTemplateLayer->assign("contenttitle",$poscodetitle);
					$PHPTemplateLayer->assign("contentposCode",$poscodeposCode);
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