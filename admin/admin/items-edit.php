<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "30";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	use includes\classes\controllers\ItemAdminController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/items-edit.htm");

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
	$id = $_GET['id'];

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:items.php?pagenumber=$pagenumber&tab=$tab");
		exit();
		}

///*****************************************************************************///
///GET CATEGORY DATA///
///*****************************************************************************///

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}

// Get data for existing item

	$item = new ItemAdminController();
	$item = $item->view($id);

	$itemType = $item->itemType;
	$name = $item->name;
	$description = $item->description;
	$image = $item->imageFileName;
	$membershipId = $item->membershipId;
	$eventId = $item->eventId ?? null;
	$membershipEventDiscountedItem = $item->membershipEventDiscountedItem;
	$itemDiscountMemberships = $item->itemDiscountMemberships;
	$sellOnline = $item->sellOnline;
	$onlinePrice = $item->onlinePrice;
	$sellAtPOS = $item->sellAtPOS;
	$posCode = $item->posCode;
	$POSPrice = $item->POSPrice;
	$taxCodeId = $item->taxCodeId;
	$donationItem = $item->donationItem;
	$donationRateId = $item->donationRateId;
	$purchaseQuantityMinimum = $item->purchaseQuantityMinimum;
	$purchaseQuantityMaximum = $item->purchaseQuantityMaximum;
	$allowNegativeStock = $item->allowNegativeStock;
	$openingStock = $item->openingStock;
	$availableDateRange = $item->availableDateRange;
	$availableFrom = $item->availableFrom;
	$availableTo = $item->availableTo;

	$categoryIds = array();

	foreach($item->categoryIds as $key => $obj)
	{
		$categoryIds[] = $obj->categoryId;
	}

	$PointOfSaleBranchIds = array();

	foreach($item->PointOfSaleBranchIds as $key => $obj)
	{
		$PointOfSaleBranchIds[] = $obj->pointOfSaleBranchId;
	}
	// echo '<pre>';echo print_r($item);echo '</pre>';exit;
		if($membershipId && $membershipId != "0")
		{
//Get Membership Title

		$requesturl = "memberships/".$membershipId;
		$requesttype = "GET";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($requestresult != "ERROR")
		{
		$requestresultarray = explode('|X|',$requestresult);
		$requestresult = json_decode($requestresultarray[1]);

			if($requestresultarray[0] == "SUCCESS")
			{
				$membershipname = $requestresult->name;
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

		$PHPTemplateLayer->assignGlobal("membershipname",htmlspecialchars($membershipname));
		}

		if($eventId && $eventId != "0")
		{
//Get Event Title

		$requesturl = "events/".$eventId;
		$requesttype = "GET";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($requestresult != "ERROR")
		{
		$requestresultarray = explode('|X|',$requestresult);
		$requestresult = json_decode($requestresultarray[1]);

			if($requestresultarray[0] == "SUCCESS")
			{
				$eventname= $requestresult->name;
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

		$PHPTemplateLayer->assignGlobal("eventname",htmlspecialchars($eventname));
		}

	$itempicture = DefaultImage($image,SETTING_DEFAULTIMAGE);
	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));
	$PHPTemplateLayer->assignGlobal("itemType",$itemType);
	$PHPTemplateLayer->assignGlobal("itemTypetext",ItemType('fancytext',$itemType));

		if($image != "")
		{
		$PHPTemplateLayer->block("IMG");
		$PHPTemplateLayer->assign("itempicture",$itempicture);

		$PHPTemplateLayer->assign("imagetypechecked1",'checked="checked"');
		}

///*****************************************************************************///
///BRANCHES///
///*****************************************************************************///

	$branchrequesturl = "pointofsalebranches";
	$branchrequesttype = "GET";
	$branchrequestbody = "";

	$branchrequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$branchrequesturl,$branchrequesttype,$branchrequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($branchrequestresult != "ERROR")
		{
		$branchrequestresultarray = explode('|X|',$branchrequestresult);
		$branchrequestresult = json_decode($branchrequestresultarray[1]);

			if($branchrequestresultarray[0] != "FAIL")
			{
			$branchtotalresults = count($branchrequestresult);

				if($branchtotalresults)
				{
				$PHPTemplateLayer->block("BRANCHES");

				if($_POST['saveedit'] || $_POST['saveexit'])
				{
					$PointOfSaleBranchIds = array();
				}

					foreach($branchrequestresult as $branchkey => $branchobject)
					{
					$PHPTemplateLayer->block("BRANCH");

					$branchtitle = $branchobject->name;
					$branchid = $branchobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST["PointOfSaleBranchIds".$branchid] == "1")
							{
							$branchselected = 'checked="checked"';

							$PointOfSaleBranchIds[] = $branchid;
							}
							else
							{
							$branchselected = '';
							}
						}
						else
						{
							if(in_array($branchid,$PointOfSaleBranchIds))
							{
							$branchselected = 'checked="checked"';
							}
							else
							{
							$branchselected = '';
							}
						}

					$PHPTemplateLayer->assign("branchid",$branchid);
					$PHPTemplateLayer->assign("branchtitle",$branchtitle);
					$PHPTemplateLayer->assign("branchselected",$branchselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOBRANCHES");
				}
			}
		}

///*****************************************************************************///
///DISCOUNTING MEMBERSHIPS///
///*****************************************************************************///

	$discountingmembershiprequesturl = "memberships";
	$discountingmembershiprequesttype = "GET";
	$discountingmembershiprequestbody = "";

	$discountingmembershiprequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$discountingmembershiprequesturl,$discountingmembershiprequesttype,$discountingmembershiprequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($discountingmembershiprequestresult != "ERROR")
		{
		$discountingmembershiprequestresultarray = explode('|X|',$discountingmembershiprequestresult);
		$discountingmembershiprequestresult = json_decode($discountingmembershiprequestresultarray[1]);

			if($discountingmembershiprequestresultarray[0] != "FAIL")
			{
			$discountingmembershiptotalresults = count($discountingmembershiprequestresult);

				if($discountingmembershiptotalresults)
				{
				$PHPTemplateLayer->block("DISCOUNTINGMEMBERSHIPS");

				$itemDiscountMemberships = array();

					foreach($discountingmembershiprequestresult as $discountingmembershipkey => $discountingmembershipobject)
					{
					$PHPTemplateLayer->block("DISCOUNTINGMEMBERSHIP");

					$discountingmembershiptitle = $discountingmembershipobject->name;
					$discountingmembershipid = $discountingmembershipobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST["itemDiscountMemberships".$discountingmembershipid] == "1")
							{
							$discountingmembershipchecked = 'checked="checked"';

							$itemDiscountMemberships[] = $discountingmembershipid;
							}
							else
							{
							$discountingmembershipchecked = '';
							}
						}
						else
						{
							if(in_array($discountingmembershipid,$itemDiscountMemberships))
							{
							$discountingmembershipchecked = 'checked="checked"';
							}
							else
							{
							$discountingmembershipchecked = '';
							}
						}

					$PHPTemplateLayer->assign("discountingmembershipid",$discountingmembershipid);
					$PHPTemplateLayer->assign("discountingmembershiptitle",$discountingmembershiptitle);
					$PHPTemplateLayer->assign("discountingmembershipchecked",$discountingmembershipchecked);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NODISCOUNTINGMEMBERSHIPS");
				}
			}
		}

///*****************************************************************************///
///TAX CODES///
///*****************************************************************************///

	$taxcoderequesturl = "taxcodes";
	$taxcoderequesttype = "GET";
	$taxcoderequestbody = "";

	$taxcoderequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$taxcoderequesturl,$taxcoderequesttype,$taxcoderequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($taxcoderequestresult != "ERROR")
		{
		$taxcoderequestresultarray = explode('|X|',$taxcoderequestresult);
		$taxcoderequestresult = json_decode($taxcoderequestresultarray[1]);

			if($taxcoderequestresultarray[0] != "FAIL")
			{
			$taxcodetotalresults = count($taxcoderequestresult);

				if($taxcodetotalresults)
				{
				$PHPTemplateLayer->block("TAXCODES");

					foreach($taxcoderequestresult as $taxcodekey => $taxcodeobject)
					{
					$PHPTemplateLayer->block("TAXCODE");

					$taxcodetitle = $taxcodeobject->name;
					$taxcodeid = $taxcodeobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST['taxCodeId'] == $taxcodeid)
							{
							$taxcodeselected = 'selected="selected"';
							}
							else
							{
							$taxcodeselected = '';
							}
						}
						else
						{
							if($taxCodeId == $taxcodeid)
							{
							$taxcodeselected = 'selected="selected"';
							}
							else
							{
							$taxcodeselected = '';
							}
						}

					$PHPTemplateLayer->assign("taxcodeid",$taxcodeid);
					$PHPTemplateLayer->assign("taxcodetitle",$taxcodetitle);
					$PHPTemplateLayer->assign("taxcodeselected",$taxcodeselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOTAXCODES");
				}
			}
		}

///*****************************************************************************///
///DONATION RATES///
///*****************************************************************************///

	$donationraterequesturl = "donationrates";
	$donationraterequesttype = "GET";
	$donationraterequestbody = "";

	$donationraterequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$donationraterequesturl,$donationraterequesttype,$donationraterequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($donationraterequestresult != "ERROR")
		{
		$donationraterequestresultarray = explode('|X|',$donationraterequestresult);
		$donationraterequestresult = json_decode($donationraterequestresultarray[1]);

			if($donationraterequestresultarray[0] != "FAIL")
			{
			$donationratetotalresults = count($donationraterequestresult);

				if($donationratetotalresults)
				{
				$PHPTemplateLayer->block("DONATIONRATES");

					foreach($donationraterequestresult as $donationratekey => $donationrateobject)
					{
					$PHPTemplateLayer->block("DONATIONRATE");

					$donationratetitle = $donationrateobject->name;
					$donationrateid = $donationrateobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST['donationRateId'] == $donationrateid)
							{
							$donationrateselected = 'selected="selected"';
							}
							else
							{
							$donationrateselected = '';
							}
						}
						else
						{
							if($donationRateId == $donationrateid)
							{
							$donationrateselected = 'selected="selected"';
							}
							else
							{
							$donationrateselected = '';
							}
						}

					$PHPTemplateLayer->assign("donationrateid",$donationrateid);
					$PHPTemplateLayer->assign("donationratetitle",$donationratetitle);
					$PHPTemplateLayer->assign("donationrateselected",$donationrateselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NODONATIONRATES");
				}
			}
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

				if($_POST['saveedit'] || $_POST['saveexit'])
				{
					$categoryIds = array();
				}

					foreach($categoryrequestresult as $categorykey => $categoryobject)
					{
					$PHPTemplateLayer->block("CATEGORY");

					$categorytitle = $categoryobject->name;
					$categoryid = $categoryobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST["categoryIds".$categoryid] == "1")
							{
							$categoryselected = 'checked="checked"';

							$categoryIds[] = $categoryid;
							}
							else
							{
							$categoryselected = '';
							}
						}
						else
						{
							if(in_array($categoryid,$categoryIds))
							{
							$categoryselected = 'checked="checked"';
							}
							else
							{
							$categoryselected = '';
							}
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
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		$description = stripslashes($_POST['description']);
		$membershipEventDiscountedItem = stripslashes($_POST['membershipEventDiscountedItem']);
		$sellOnline = stripslashes($_POST['sellOnline']);
		$onlinePrice = stripslashes($_POST['onlinePrice']);
		$sellAtPOS = stripslashes($_POST['sellAtPOS']);
		$posCode = stripslashes($_POST['posCode']);
		$POSPrice = stripslashes($_POST['POSPrice']);
		$taxCodeId = stripslashes($_POST['taxCodeId']);
		$donationItem = stripslashes($_POST['donationItem']);
		$donationRateId = stripslashes($_POST['donationRateId']);
		$purchaseQuantityMinimum = stripslashes($_POST['purchaseQuantityMinimum']);
		$purchaseQuantityMaximum = stripslashes($_POST['purchaseQuantityMaximum']);
		$allowNegativeStock = stripslashes($_POST['allowNegativeStock']);
		$openingStock = stripslashes($_POST['openingStock']);
		$availableDateRange = stripslashes($_POST['availableDateRange']);
		$availableFrom = stripslashes($_POST['availableFrom']);
		$availableTo = stripslashes($_POST['availableTo']);
		$imagetype = stripslashes($_POST['imagetype']);
		}

			if($membershipEventDiscountedItem)
			{
			$PHPTemplateLayer->assignGlobal("membershipEventDiscountedItemchecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("membershipEventDiscountedItemchecked",'');
			}

			if($sellOnline)
			{
			$PHPTemplateLayer->assignGlobal("sellOnlinechecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("sellOnlinechecked",'');
			}

			if($sellAtPOS)
			{
			$PHPTemplateLayer->assignGlobal("sellAtPOSchecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("sellAtPOSchecked",'');
			}

			if($donationItem)
			{
			$PHPTemplateLayer->assignGlobal("donationItemchecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("donationItemchecked",'');
			}

			if($membershipEventDiscountedItem)
			{
			$PHPTemplateLayer->assignGlobal("membershipEventDiscountedItemchecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("membershipEventDiscountedItemchecked",'');
			}

			if($allowNegativeStock)
			{
			$PHPTemplateLayer->assignGlobal("allowNegativeStockchecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("allowNegativeStockchecked",'');
			}

			if($availableDateRange)
			{
			$PHPTemplateLayer->assignGlobal("availableDateRangechecked",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("availableDateRangechecked",'');
			}

		$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($name));
		$PHPTemplateLayer->assignGlobal("description",$description);
		$PHPTemplateLayer->assignGlobal("onlinePrice",htmlspecialchars($onlinePrice));
		$PHPTemplateLayer->assignGlobal("posCode",htmlspecialchars($posCode));
		$PHPTemplateLayer->assignGlobal("POSPrice",htmlspecialchars($POSPrice));
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMinimum",htmlspecialchars($purchaseQuantityMinimum));
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMaximum",htmlspecialchars($purchaseQuantityMaximum));
		$PHPTemplateLayer->assignGlobal("openingStock",htmlspecialchars($openingStock));
		$PHPTemplateLayer->assignGlobal("availableFrom",htmlspecialchars($availableFrom));
		$PHPTemplateLayer->assignGlobal("availableTo",htmlspecialchars($availableTo));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}
			
			if($description == "" || !form_valid_string('VARCHAR',$description))
			{
			$error = "1";
			$error2 = "1";
			}

			if(is_uploaded_file($_FILES['imageFileName']['tmp_name']))
			{
				if($_FILES['imageFileName']['error'] > 0) 
				{
				$error = "1";
				$error3 = "1";
				}

				if(process_uploadfilemimetype($_FILES["imageFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error3 = "1";
				}

				if($_FILES['imageFileName']['size'] > 2000000)
				{
				$error = "1";
				$error3 = "1";
				}
			}
			else
			{
				if($imagetype == "NEW")
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if($itemType == "3")
			{

				if($membershipEventDiscountedItem)
				{
					if(!$itemDiscountMemberships || $itemDiscountMemberships == "")
					{
					$error = "1";
					$error6 = "1";
					}
				}
			}

			if($sellOnline)
			{
				if(!$onlinePrice || valid_moneyinput($onlinePrice,'0.01','','YES') == "FALSE")
				{
				$error = "1";
				$error7 = "1";
				}

			}

			if($sellAtPOS)
			{
				if($posCode == "" || !form_valid_string('VARCHAR',$posCode))
				{
				$error = "1";
				$error8 = "1";
				}

				if(!$POSPrice || valid_moneyinput($POSPrice,'0.01','','YES') == "FALSE")
				{
				$error = "1";
				$error9 = "1";
				}
			}

			if(!$taxCodeId || $taxCodeId == "")
			{
			$error = "1";
			$error10 = "1";
			}

			if(!$purchaseQuantityMinimum || valid_Integer($purchaseQuantityMinimum,'VALUE','1','99') == "FALSE")
			{
			$error = "1";
			$error11 = "1";
			}

			if(!$purchaseQuantityMaximum || valid_Integer($purchaseQuantityMaximum,'VALUE','1','99') == "FALSE")
			{
			$error = "1";
			$error12 = "1";
			}

			if($itemType == "1")
			{
				if(!$openingStock || valid_Integer($openingStock,'VALUE','1','999') == "FALSE")
				{
				$error = "1";
				$error13 = "1";
				}
			}

			if($availableDateRange)
			{
				if(!$availableFrom || $availableFrom == "" || form_valid_dateinput($availableFrom) == "FALSE")
				{
				$error = "1";
				$error14 = "1";
				}

				if(!$availableTo || $availableTo == "" || form_valid_dateinput($availableTo) == "FALSE")
				{
				$error = "1";
				$error15 = "1";
				}
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);
			$description = form_process_WYSIWYG($description);
			$onlinePrice = form_process_makesafe($onlinePrice);
			$posCode = form_process_makesafe($posCode);
			$POSPrice = form_process_makesafe($POSPrice);
			$purchaseQuantityMinimum = form_process_makesafe($purchaseQuantityMinimum);
			$purchaseQuantityMaximum = form_process_makesafe($purchaseQuantityMaximum);
			$openingStock = form_process_makesafe($openingStock);
			$availableFrom = form_process_makesafe($availableFrom);
			$availableTo = form_process_makesafe($availableTo);

			$availableFrom = DateTimePicker('JS',$availableFrom,'PHP');
			$availableTo = DateTimePicker('JS',$availableTo,'PHP');

			$membershipEventDiscountedItem = ReturnZero($membershipEventDiscountedItem);
			$sellOnline = ReturnZero($sellOnline);
			$sellAtPOS = ReturnZero($sellAtPOS);
			$donationItem = ReturnZero($donationItem);
			$allowNegativeStock = ReturnZero($allowNegativeStock);
			$availableDateRange = ReturnZero($availableDateRange);

				if(is_uploaded_file($_FILES['imageFileName']['tmp_name']))
				{
				$imageFileName = process_uploadfilename($_FILES["imageFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['imageFileName']["tmp_name"],"../img/".$imageFileName);
				}
				else
				{
				$imageFileName = "";
				}

				$saveimageFileName = ProcessEditImage($imagetype,$image,$imageFileName);

// SORT OUT DATA!!!

				if($itemType == "3")
				{
					if(!$membershipEventDiscountedItem)
					{
					$itemDiscountMemberships = "";
					}
				}
				else
				{
				$membershipEventDiscountedItem = "";
				$itemDiscountMemberships = "";
				}

				if(!$sellOnline)
				{
				$onlinePrice = "";
				}

				if(!$sellAtPOS)
				{
				$posCode = "";
				$POSPrice = "";
				$PointOfSaleBranchIds = "";
				}

				if($itemType != "1")
				{
				$allowNegativeStock = "";
				$openingStock = "";
				}

				if($donationItem)
				{
				$donationRateId = "";
				}

				if(!$availableDateRange)
				{
				$availableFrom = "";
				$availableTo = "";
				}
				else
				{
				$availableFrom = AvailableDate($availableFrom);
				$availableTo = AvailableDate($availableTo);
				}

// API ACTION: CREATE ITEM

			$requestdata = [
			"itemType"             => $itemType,
			"name"             => $name,
			"description"             => $description,
			"imageFileName"             => $saveimageFileName,
			"categoryIds"             => $categoryIds,
			"membershipEventDiscountedItem"             => $membershipEventDiscountedItem,
			"itemDiscountMemberships"             => $itemDiscountMemberships,
			"sellOnline"             => $sellOnline,
			"onlinePrice"             => $onlinePrice,
			"sellAtPOS"             => $sellAtPOS,
			"posCode"             => $posCode,
			"POSPrice"             => $POSPrice,
			"PointOfSaleBranchIds"             => $PointOfSaleBranchIds,
			"taxCodeId"             => $taxCodeId,
			"donationItem"             => $donationItem,
			"donationRateId"             => $donationRateId,
			"purchaseQuantityMinimum"             => $purchaseQuantityMinimum,
			"purchaseQuantityMaximum"             => $purchaseQuantityMaximum,
			"allowNegativeStock"             => $allowNegativeStock,
			"openingStock"             => $openingStock,
			"availableDateRange"             => $availableDateRange,
			"availableFrom"             => $availableFrom,
			"availableTo"             => $availableTo,
			];

			$requesturl = "items/".$id;
			$requesttype = "PUT";
			$requestbody = $requestdata;

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

				$requestresult = json_decode($requestresultarray[1],'true');

					if($requestresultarray[0] == "SUCCESS")
					{
					$newid = $requestresult["id"];

						$imageFileData = $_POST['imageFileData'];

						if (($imagetype == "NEW" || $imagetype == "") && $imageFileData !== "") {

							$imagerequestbody = [
								"originalFileName"     	=> $saveimageFileName,
								"categoryId"		   	=> null,
								"eventId"				=> null,
								"itemId"				=> $newid,
								"imageData"				=> $imageFileData,
							];

							$imagerequesturl = "";
							
							if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
							{
								$apierror = 1;

								header("Location:items-edit.php?pagenumber=$pagenumber&tab=$tab&success=2&id=$newid");
								exit();
							}
						}

						if ($imagetype == "NONE") {
							$imagerequestbody = [
								"categoryId"		   	=> null,
								"eventId"				=> null,
								"itemId"				=> $newid,
							];

							$imagerequesturl = "clearimage";
							
							if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
							{
								$apierror = 1;

								header("Location:items-edit.php?pagenumber=$pagenumber&tab=$tab&success=2&id=$newid");
								exit();
							}
						}


						if($_POST['saveedit'])
						{
						header("Location:items-edit.php?pagenumber=$pagenumber&tab=$tab&success=2&id=$newid");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:items.php?pagenumber=$pagenumber&tab=$tab&success=1");
						exit();
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
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("nameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("nameclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("descriptionclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("descriptionclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("imageFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("imageFileNameclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("itemDiscountMembershipsclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("itemDiscountMembershipsclass",'textbox-noerror');
		}

		if($error7 != "")
		{
		$PHPTemplateLayer->assignGlobal("onlinePriceclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("onlinePriceclass",'textbox-noerror');
		}

		if($error8 != "")
		{
		$PHPTemplateLayer->assignGlobal("posCodeclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posCodeclass",'textbox-noerror');
		}

		if($error9 != "")
		{
		$PHPTemplateLayer->assignGlobal("POSPriceclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("POSPriceclass",'textbox-noerror');
		}

		if($error10 != "")
		{
		$PHPTemplateLayer->assignGlobal("taxCodeIdclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("taxCodeIdclass",'textbox-noerror');
		}

		if($error11 != "")
		{
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMinimumclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMinimumclass",'textbox-noerror');
		}

		if($error12 != "")
		{
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMaximumclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMaximumclass",'textbox-noerror');
		}

		if($error13 != "")
		{
		$PHPTemplateLayer->assignGlobal("openingStockclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("openingStockclass",'textbox-noerror');
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

			if($error1 == "1")
			{
			$error1 = 'Please enter a name';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter a description in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error2 == "1")
			{
			$error2 = 'Please enter a description in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error6 == "1")
			{
			$error6 = 'Please select at least one option.';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);

			if($error7 == "1")
			{
			$error7 = 'Please enter a value.';
			}

		$PHPTemplateLayer->assignGlobal("error7",$error7);

			if($error8 == "1")
			{
			$error8 = 'Please enter a value.';
			}

		$PHPTemplateLayer->assignGlobal("error8",$error8);

			if($error9 == "1")
			{
			$error9 = 'Please enter a value.';
			}

		$PHPTemplateLayer->assignGlobal("error9",$error9);

			if($error10 == "1")
			{
			$error10 = 'Please select a tax code.';
			}

		$PHPTemplateLayer->assignGlobal("error10",$error10);

			if($error11 == "1")
			{
			$error11 = 'Please enter a value between 1 and 99.';
			}

		$PHPTemplateLayer->assignGlobal("error11",$error11);

			if($error12 == "1")
			{
			$error12 = 'Please enter a value between 1 and 99.';
			}

		$PHPTemplateLayer->assignGlobal("error12",$error12);

			if($error13 == "1")
			{
			$error13 = 'Please enter a value between 1 and 999.';
			}

		$PHPTemplateLayer->assignGlobal("error13",$error13);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>