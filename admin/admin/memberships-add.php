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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/memberships-add.htm");

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

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:memberships.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if(!$_POST['saveedit'] && !$_POST['saveexit'])
		{
		$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked3",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked4",'');
		$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked0",'');

		$PHPTemplateLayer->assignGlobal("durationunitsselected1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("durationunitsselected2",'');
		$PHPTemplateLayer->assignGlobal("durationunitsselected3",'');

		$PHPTemplateLayer->assignGlobal("memberTypeschecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("memberTypeschecked2",'');
		$PHPTemplateLayer->assignGlobal("memberTypeschecked3",'');
		}
		else
		{
		$name = stripslashes($_POST['name']);
		$membershipLengthType = stripslashes($_POST['membershipLengthType']);
		$membershipLength = stripslashes($_POST['membershipLength']);
		$durationunits = stripslashes($_POST['durationunits']);
		$numberOfPeople = stripslashes($_POST['numberOfPeople']);
		$memberTypes = stripslashes($_POST['memberTypes']);
		$maximumAdults = stripslashes($_POST['maximumAdults']);
		$maximumChildren = stripslashes($_POST['maximumChildren']);
		$specificExpirationDate = stripslashes($_POST['specificExpirationDate']);

			if($membershipLengthType == "3")
			{
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked3",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked4",'');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked0",'');
			}
			elseif($membershipLengthType == "4")
			{
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked4",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked0",'');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked3",'');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked4",'');
			$PHPTemplateLayer->assignGlobal("membershipLengthTypechecked0",'checked="checked"');
			}

			if($durationunits == "days")
			{
			$PHPTemplateLayer->assignGlobal("durationunitsselected1",'selected="selected"');
			$PHPTemplateLayer->assignGlobal("durationunitsselected2",'');
			$PHPTemplateLayer->assignGlobal("durationunitsselected3",'');
			}
			elseif($durationunits == "months")
			{
			$PHPTemplateLayer->assignGlobal("durationunitsselected1",'');
			$PHPTemplateLayer->assignGlobal("durationunitsselected2",'selected="selected"');
			$PHPTemplateLayer->assignGlobal("durationunitsselected3",'');
			}
			elseif($durationunits == "years")
			{
			$PHPTemplateLayer->assignGlobal("durationunitsselected1",'');
			$PHPTemplateLayer->assignGlobal("durationunitsselected2",'');
			$PHPTemplateLayer->assignGlobal("durationunitsselected3",'selected="selected"');
			}

			if($memberTypes == "1")
			{
			$PHPTemplateLayer->assignGlobal("memberTypeschecked1",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked2",'');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked3",'');
			}
			elseif($memberTypes == "2")
			{
			$PHPTemplateLayer->assignGlobal("memberTypeschecked1",'');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked2",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked3",'');
			}
			elseif($memberTypes == "3")
			{
			$PHPTemplateLayer->assignGlobal("memberTypeschecked1",'');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked2",'');
			$PHPTemplateLayer->assignGlobal("memberTypeschecked3",'checked="checked"');
			}

		$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($name));
		$PHPTemplateLayer->assignGlobal("membershipLength",htmlspecialchars($membershipLength));
		$PHPTemplateLayer->assignGlobal("numberOfPeople",htmlspecialchars($numberOfPeople));
		$PHPTemplateLayer->assignGlobal("maximumAdults",htmlspecialchars($maximumAdults));
		$PHPTemplateLayer->assignGlobal("maximumChildren",htmlspecialchars($maximumChildren));
		$PHPTemplateLayer->assignGlobal("specificExpirationDate",htmlspecialchars($specificExpirationDate));

			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}

			if($membershipLengthType == "4")
			{
				if(!$specificExpirationDate || $specificExpirationDate == "" || form_valid_dateinput($specificExpirationDate) == "FALSE")
				{
				$error = "1";
				$error2 = "1";
				}
			}
			elseif($membershipLengthType == "0")
			{
				if(!$membershipLength || $membershipLength == "" || valid_Integer($membershipLength,'VALUE','1','999') == "FALSE")
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if(!$numberOfPeople || $numberOfPeople == "" || valid_Integer($numberOfPeople,'VALUE','1','999') == "FALSE")
			{
			$error = "1";
			$error4 = "1";
			}

			if($memberTypes == "1")
			{
				if(!$maximumAdults || $maximumAdults == "" || valid_Integer($maximumAdults,'VALUE','1','999') == "FALSE")
				{
				$error = "1";
				$error5 = "1";
				}
				elseif($maximumAdults > $numberOfPeople)
				{
				$error = "1";
				$error5 = "2";
				}

				if(!$maximumChildren || $maximumChildren == "" || valid_Integer($maximumChildren,'VALUE','1','999') == "FALSE")
				{
				$error = "1";
				$error6 = "1";
				}
				elseif($maximumChildren > $numberOfPeople)
				{
				$error = "1";
				$error6 = "2";
				}
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);
			$numberOfPeople = form_process_makesafe($numberOfPeople);
			$specificExpirationDate = form_process_makesafe($specificExpirationDate);

			$specificExpirationDate = DateTimePicker('JS',$specificExpirationDate,'PHP');

// API ACTION: CREATE CATEGORY

				if($membershipLengthType == "4")
				{
				$specificExpirationDate = form_process_makesafe($specificExpirationDate);
				}
				else
				{
				$specificExpirationDate = "";
				}

				if($membershipLengthType == "0")
				{
				$membershipLength = form_process_makesafe($membershipLength);

					if($durationunits == "days")
					{
					$membershipLengthType = "0";
					}
					elseif($durationunits == "months")
					{
					$membershipLengthType = "1";
					}
					elseif($durationunits == "years")
					{
					$membershipLengthType = "2";
					}
				}
				else
				{
				$membershipLength = "0";
				}

				if($memberTypes == "1")
				{
				$maximumAdults = form_process_makesafe($maximumAdults);
				$maximumChildren = form_process_makesafe($maximumChildren);
				}
				elseif($memberTypes == "2")
				{
				$maximumAdults = $numberOfPeople;
				$maximumChildren = "0";
				}
				elseif($memberTypes == "3")
				{
				$maximumAdults = "0";
				$maximumChildren = $numberOfPeople;
				}

			$requestdata = [
			"name"             => $name,
			"membershipLengthType" => $membershipLengthType,
			"specificExpirationDate" => substr(date(DATE_ATOM, strtotime($specificExpirationDate)), 0, -6),
			"membershipLength" => $membershipLength,
			"numberOfPeople" => $numberOfPeople,
			"memberTypes" => $memberTypes,
			"maximumAdults" => $maximumAdults,
			"maximumChildren" => $maximumChildren,
			"primarymemberNameCaptureType" => 0,
			"primarymemberDateOfBirthCaptureType" => 0,
			"primarymemberPhoneCaptureType" => 0,
			"primarymemberEmailCaptureType" => 0,
			"primarymemberMarketingCaptureType" => 0,
			"posPrimarymemberPhotoCaptureType" => 0,
			"posPrimarymemberMagstripeCaptureType" => 0,
			];

				if($memberTypes == "1")
				{
				$membertypedata = [
				"additionalmemberAdultNameCaptureType" => 0,
				"additionalmemberAdultDateOfBirthCaptureType" => 0,
				"additionalmemberAdultPhoneCaptureType" => 0,
				"additionalmemberAdultEmailCaptureType" => 0,
				"additionalmemberAdultMarketingCaptureType" => 0,
				"posAdditionalmemberAdultPhotoCaptureType" => 0,
				"posAdditionalmemberAdultMagstripeCaptureType" => 0,
				"additionalmemberChildNameCaptureType" => 0,
				"additionalmemberChildDateOfBirthCaptureType" => 0,
				"additionalmemberChildPhoneCaptureType" => 0,
				"additionalmemberChildEmailCaptureType" => 0,
				"additionalmemberChildMarketingCaptureType" => 0,
				"posAdditionalmemberChildPhotoCaptureType" => 0,
				"posAdditionalmemberChildMagstripeCaptureType" => 0,
				];

				$requestdata = array_merge($requestdata, $membertypedata); 
				}

			$requesturl = "memberships";
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

					header("Location:memberships-edit-primarymember.php?pagenumber=$pagenumber&success=1&id=$newid");
					exit();
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
		$PHPTemplateLayer->assignGlobal("specificExpirationDateclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("specificExpirationDateclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("membershipLengthclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("membershipLengthclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("numberOfPeopleclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("numberOfPeopleclass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("maximumAdultsclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("maximumAdultsclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("maximumChildrenclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("maximumChildrenclass",'textbox-noerror');
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
			$error2 = 'Please enter a valid date';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter a number between 1 and 999';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter a number between 1 and 999';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please enter a number between 1 and 999';
			}
			elseif($error5 == "2")
			{
			$error5 = 'The maximum adults cannot exceed the total number of people';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

			if($error6 == "1")
			{
			$error6 = 'Please enter a number between 1 and 999';
			}
			elseif($error6 == "2")
			{
			$error6 = 'The maximum adults cannot exceed the total number of people';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>