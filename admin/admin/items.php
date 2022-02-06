<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "10";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/items.htm");

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
	$tab = $_GET['tab'];

///*****************************************************************************///
///PROCESS ADD NEW///
///*****************************************************************************///

		if($_POST['add'])
		{
		header("Location:items-add.php?tab=$tab");
		exit();
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: ASSIGN TAB///
///*****************************************************************************///

		if($tab == "memberships")
		{
		$PHPTemplateLayer->assignGlobal("tabticketsclass",'');
		$PHPTemplateLayer->assignGlobal("tabmembershipsclass",'class="active"');
		$PHPTemplateLayer->assignGlobal("tabeventsclass}",'');
		$PHPTemplateLayer->assignGlobal("tabproductsclass",'');
		}
		elseif($tab == "events")
		{
		$PHPTemplateLayer->assignGlobal("tabticketsclass",'');
		$PHPTemplateLayer->assignGlobal("tabmembershipsclass",'');
		$PHPTemplateLayer->assignGlobal("tabeventsclass",'class="active"');
		$PHPTemplateLayer->assignGlobal("tabproductsclass",'');
		}
		elseif($tab == "products")
		{
		$PHPTemplateLayer->assignGlobal("tabticketsclass",'');
		$PHPTemplateLayer->assignGlobal("tabmembershipsclass",'');
		$PHPTemplateLayer->assignGlobal("tabeventsclass}",'');
		$PHPTemplateLayer->assignGlobal("tabproductsclass",'class="active"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("tabticketsclass",'class="active"');
		$PHPTemplateLayer->assignGlobal("tabmembershipsclass",'');
		$PHPTemplateLayer->assignGlobal("tabeventsclass}",'');
		$PHPTemplateLayer->assignGlobal("tabproductsclass",'');
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORIES///
///*****************************************************************************///

	$tabvalue = ItemType('tocode',$tab);

	$baseurl = "items.php?tab=$tab&pagenumber=";

		if(!$pagenumber)
		{
		$pagenumber = "0";
		}
		else
		{
		$pagenumber = $pagenumber-1;
		}

	$requesturl = "items?include=Event&itemType=".$tabvalue."&pageNumber=".$pagenumber."&pageSize=".ADMIN_PERPAGE;
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
				$nextrequesturl = "items?include=Event&itemType=".$tabvalue."&pageNumber=".($pagenumber+1)."&pageSize=".ADMIN_PERPAGE;
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
			$PHPTemplateLayer->assignGlobal("tab",$tab);

				if($totalresults)
				{
				$PHPTemplateLayer->block("CONTENTTABLE");

				usort($requestresult, 'process_content_displayOrder');

				$itemcount = 0;

					if($tab == "events")
					{
					$PHPTemplateLayer->block("EVENTHEADER");
					}

					foreach($requestresult as $itemkey => $itemobject)
					{
					$PHPTemplateLayer->block("CONTENTROW");

					$itemcount = $itemcount+1;

					$itemid = $itemobject->id;
					$itemtitle = $itemobject->name;
					$itemdescription = $itemobject->description;
					$itemimage = $itemobject->imageFileName;

					$itempicture = DefaultImage($itemimage,SETTING_DEFAULTIMAGE);

					$PHPTemplateLayer->assign("contentid",$itemid);
					$PHPTemplateLayer->assign("contentimage",$itempicture);
					$PHPTemplateLayer->assign("contenttitle",$itemtitle);

						if($tab == "events")
						{
						$PHPTemplateLayer->block("EVENTROW");
						$PHPTemplateLayer->assign("contentevent",$itemobject->event->name);
						}
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