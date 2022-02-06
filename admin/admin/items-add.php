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

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/items-add.htm");

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
		header("Location:memberships.php?pagenumber=$pagenumber");
		exit();
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

				$PointOfSaleBranchIds = array();

					foreach($branchrequestresult as $branchkey => $branchobject)
					{
					$PHPTemplateLayer->block("BRANCH");

					$branchtitle = $branchobject->name;
					$branchid = $branchobject->id;

						if($_POST["PointOfSaleBranchIds".$branchid] == "1")
						{
						$branchselected = 'checked="checked"';

						$PointOfSaleBranchIds[] = $branchid;
						}
						else
						{
						$branchselected = '';
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
///EVENT///
///*****************************************************************************///

	$eventrequesturl = "events";
	$eventrequesttype = "GET";
	$eventrequestbody = "";

	$eventrequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$eventrequesturl,$eventrequesttype,$eventrequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($eventrequestresult != "ERROR")
		{
		$eventrequestresultarray = explode('|X|',$eventrequestresult);
		$eventrequestresult = json_decode($eventrequestresultarray[1]);

			if($eventrequestresultarray[0] != "FAIL")
			{
			$eventtotalresults = count($eventrequestresult);

				if($eventtotalresults)
				{
				$PHPTemplateLayer->block("EVENTS");

					foreach($eventrequestresult as $eventkey => $eventobject)
					{
					$PHPTemplateLayer->block("EVENT");

					$eventtitle = $eventobject->name;
					$eventid = $eventobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST['eventId'] == $eventid)
							{
							$eventselected = 'selected="selected"';
							}
							else
							{
							$eventselected = '';
							}
						}
						else
						{
						$eventselected = "";
						}

					$PHPTemplateLayer->assign("eventid",$eventid);
					$PHPTemplateLayer->assign("eventtitle",$eventtitle);
					$PHPTemplateLayer->assign("eventselected",$eventselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOEVENTS");
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

						if($_POST["itemDiscountMemberships".$discountingmembershipid] == "1")
						{
						$discountingmembershipchecked = 'checked="checked"';

						$itemDiscountMemberships[] = $discountingmembershipid;
						}
						else
						{
						$discountingmembershipchecked = '';
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
///MEMBERSHIPS///
///*****************************************************************************///

	$membershiprequesturl = "memberships";
	$membershiprequesttype = "GET";
	$membershiprequestbody = "";

	$membershiprequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$membershiprequesturl,$membershiprequesttype,$membershiprequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($membershiprequestresult != "ERROR")
		{
		$membershiprequestresultarray = explode('|X|',$membershiprequestresult);
		$membershiprequestresult = json_decode($membershiprequestresultarray[1]);

			if($membershiprequestresultarray[0] != "FAIL")
			{
			$membershiptotalresults = count($membershiprequestresult);

				if($membershiptotalresults)
				{
				$PHPTemplateLayer->block("MEMBERSHIPS");

					foreach($membershiprequestresult as $membershipkey => $membershipobject)
					{
					$PHPTemplateLayer->block("MEMBERSHIP");

					$membershiptitle = $membershipobject->name;
					$membershipid = $membershipobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST['membershipId'] == $membershipid)
							{
							$membershipselected = 'selected="selected"';
							}
							else
							{
							$membershipselected = '';
							}
						}
						else
						{
						$membershipselected = "";
						}

					$PHPTemplateLayer->assign("membershipid",$membershipid);
					$PHPTemplateLayer->assign("membershiptitle",$membershiptitle);
					$PHPTemplateLayer->assign("membershipselected",$membershipselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOMEMBERSHIPS");
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
						$taxcodeselected = "";
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
						$donationrateselected = "";
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
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if(!$_POST['saveedit'] && !$_POST['saveexit'])
		{
			if($tab == "memberships")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'checked="checked"');
			}
			elseif($tab == "events")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
			}
			elseif($tab == "products")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
			}
		}
		else
		{
		$itemType = stripslashes($_POST['itemType']);
		$name = stripslashes($_POST['name']);
		$description = stripslashes($_POST['description']);
		$membershipId = stripslashes($_POST['membershipId']);
		$eventId = stripslashes($_POST['eventId']);
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

			if($itemType == "4")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'checked="checked"');
			}
			elseif($itemType == "3")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
			}
			elseif($itemType == "1")
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("itemTypechecked1",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked2",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("itemTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("itemTypechecked4",'');
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
		$PHPTemplateLayer->assignGlobal("description",htmlspecialchars($description));
		$PHPTemplateLayer->assignGlobal("onlinePrice",htmlspecialchars($onlinePrice));
		$PHPTemplateLayer->assignGlobal("posCode",htmlspecialchars($posCode));
		$PHPTemplateLayer->assignGlobal("POSPrice",htmlspecialchars($POSPrice));
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMinimum",htmlspecialchars($purchaseQuantityMinimum));
		$PHPTemplateLayer->assignGlobal("purchaseQuantityMaximum",htmlspecialchars($purchaseQuantityMaximum));
		$PHPTemplateLayer->assignGlobal("openingStock",htmlspecialchars($openingStock));
		$PHPTemplateLayer->assignGlobal("availableFrom",htmlspecialchars($availableFrom));
		$PHPTemplateLayer->assignGlobal("availableTo",htmlspecialchars($availableTo));

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

			if($_FILES['imageFileName']['name'] != "" && $_FILES['imageFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error3 = "1";
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

			if($itemType == "4")
			{
				if(!$membershipId || $membershipId == "")
				{
				$error = "1";
				$error4 = "1";
				}
			}
			elseif($itemType == "3")
			{
				if(!$eventId || $eventId == "")
				{
				$error = "1";
				$error5 = "1";
				}

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

// SORT OUT DATA!!!

				if($itemType != "4")
				{
				$membershipId = "";
				}

				if($itemType == "3")
				{
					if(!$membershipEventDiscountedItem)
					{
					$itemDiscountMemberships = "";
					}
				}
				else
				{
				$eventId = "";
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
			"imageFileName"             => $imageFileName,
			"categoryIds"             => $categoryIds,
			"membershipId"             => $membershipId,
			"eventId"             => $eventId,
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

			$requesturl = "items";
			$requesttype = "POST";
			$requestbody = $requestdata;

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

				$requestresult = json_decode($requestresultarray[1],'true');

					if($requestresultarray[0] == "SUCCESS")
					{
						$newid = $requestresult["id"];

						foreach($categoryIds as $obj)
						{
							if(!CategoriesItemsSetDisplayOrder($obj,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
							{
								$apierror = 1;
							}
						}

						$imageFileData = $_POST['imageFileData'];

						$imagerequestbody = [
							"originalFileName"     	=> $_FILES['imageFileName']['name'],
							"categoryId"		   	=> null,
							"eventId"				=> null,
							"itemId"				=> $newid,
							"imageData"				=> $imageFileData,
						];

						$imagerequesturl = "";

						if($imageFileData != "" && !UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
							$apierror = 1;

							header("Location:items-edit.php?pagenumber=$pagenumber&tab=$tab&error=1&id=$newid");
							exit();
						} else {
							if($_POST['saveedit'])
							{
							header("Location:items-edit.php?pagenumber=$pagenumber&tab=$tab&success=1&id=$newid");
							exit();
							}
							elseif($_POST['saveexit'])
							{
							header("Location:items.php?pagenumber=$pagenumber&tab=$tab&success=1");
							exit();
							}
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

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("membershipIdclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("membershipIdclass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("eventIdclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("eventIdclass",'textbox-noerror');
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

			if($error4 == "1")
			{
			$error4 = 'Please select a membership type.';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please select an event.';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

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