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

	use includes\classes\controllers\MembershipTypeController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/memberships-edit-additionalchild.htm");

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
	$tab = $_GET['tab'];

	$PHPTemplateLayer->assignGlobal("id",$id);
	$PHPTemplateLayer->assignGlobal("pagenumber",$pagenumber);
	$PHPTemplateLayer->assignGlobal("tab",$tab);

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:memberships.php?pagenumber=$pagenumber");
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

// Get data for existing membership

	$membership = new membershipTypeController();
	$membership = $membership->view($id);

	$name = $membership->name;
	$membershipLength = $membership->membershipLength;
	$membershipLengthType = $membership->membershipLengthType;
	$specificExpirationDate = $membership->specificExpirationDat;
	$numberOfPeople = $membership->numberOfPeople;
	$memberTypes = $membership->memberTypes;
	$maximumAdults = $membership->maximumAdults;
	$maximumChildren = $membership->maximumChildren;
	$primaryMemberNameCaptureType = $membership->primaryMemberNameCaptureType;
	$primaryMemberDateOfBirthCaptureType = $membership->primaryMemberDateOfBirthCaptureType;
	$primaryMemberPhoneCaptureType = $membership->primaryMemberPhoneCaptureType;
	$primaryMemberEmailCaptureType = $membership->primaryMemberEmailCaptureType;
	$primaryMemberMarketingCaptureType = $membership->primaryMemberMarketingCaptureType;
	$posPrimaryMemberPhotoCaptureType = $membership->posPrimaryMemberPhotoCaptureType;
	$posPrimaryMemberMagstripeCaptureType = $membership->posPrimaryMemberMagstripeCaptureType;
	$additionalMemberAdultNameCaptureType = $membership->additionalMemberAdultNameCaptureType;
	$additionalMemberAdultDateOfBirthCaptureType = $membership->additionalMemberAdultDateOfBirthCaptureType;
	$additionalMemberAdultPhoneCaptureType = $membership->additionalMemberAdultPhoneCaptureType;
	$additionalMemberAdultEmailCaptureType = $membership->additionalMemberAdultEmailCaptureType;
	$additionalMemberAdultMarketingCaptureType = $membership->additionalMemberAdultMarketingCaptureType;
	$posAdditionalMemberAdultPhotoCaptureType = $membership->posAdditionalMemberAdultPhotoCaptureType;
	$posAdditionalMemberAdultMagstripeCaptureType = $membership->posAdditionalMemberAdultMagstripeCaptureType;
	$additionalMemberChildNameCaptureType = $membership->additionalMemberChildNameCaptureType;
	$additionalMemberChildDateOfBirthCaptureType = $membership->additionalMemberChildDateOfBirthCaptureType;
	$additionalMemberChildPhoneCaptureType = $membership->additionalMemberChildPhoneCaptureType;
	$additionalMemberChildEmailCaptureType = $membership->additionalMemberChildEmailCaptureType;
	$additionalMemberChildMarketingCaptureType = $membership->additionalMemberChildMarketingCaptureType;
	$posAdditionalMemberChildPhotoCaptureType = $membership->posAdditionalMemberChildPhotoCaptureType;
	$posAdditionalMemberChildMagstripeCaptureType = $membership->posAdditionalMemberChildMagstripeCaptureType;

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));

		if($additionalMemberChildNameCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked0",'');
		}
		elseif($additionalMemberChildNameCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildNameCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberChildMarketingCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildMarketingCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildMarketingCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildMarketingCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildMarketingCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberChildPhoneCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked0",'');
		}
		elseif($additionalMemberChildPhoneCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildPhoneCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberChildEmailCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked0",'');
		}
		elseif($additionalMemberChildEmailCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildEmailCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberChildDateOfBirthCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked0",'');
		}
		elseif($additionalMemberChildDateOfBirthCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberChildDateOfBirthCaptureTypechecked0",'checked="checked"');
		}

		if($posAdditionalMemberChildMagstripeCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked0",'');
		}
		elseif($posAdditionalMemberChildMagstripeCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildMagstripeCaptureTypechecked0",'checked="checked"');
		}

		if($posAdditionalMemberChildPhotoCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked0",'');
		}
		elseif($posAdditionalMemberChildPhotoCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberChildPhotoCaptureTypechecked0",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if(!$error)
			{
			$additionalMemberChildNameCaptureType = stripslashes($_POST['additionalMemberChildNameCaptureType']);
			$additionalMemberChildMarketingCaptureType = stripslashes($_POST['additionalMemberChildMarketingCaptureType']);
			$additionalMemberChildPhoneCaptureType = stripslashes($_POST['additionalMemberChildPhoneCaptureType']);
			$additionalMemberChildEmailCaptureType = stripslashes($_POST['additionalMemberChildEmailCaptureType']);
			$additionalMemberChildDateOfBirthCaptureType = stripslashes($_POST['additionalMemberChildDateOfBirthCaptureType']);
			$posAdditionalMemberChildMagstripeCaptureType = stripslashes($_POST['posAdditionalMemberChildMagstripeCaptureType']);
			$posAdditionalMemberChildPhotoCaptureType = stripslashes($_POST['posAdditionalMemberChildPhotoCaptureType']);

			$additionalMemberChildNameCaptureType = form_process_makesafe($additionalMemberChildNameCaptureType);
			$additionalMemberChildMarketingCaptureType = form_process_makesafe($additionalMemberChildMarketingCaptureType);
			$additionalMemberChildPhoneCaptureType = form_process_makesafe($additionalMemberChildPhoneCaptureType);
			$additionalMemberChildEmailCaptureType = form_process_makesafe($additionalMemberChildEmailCaptureType);
			$additionalMemberChildDateOfBirthCaptureType = form_process_makesafe($additionalMemberChildDateOfBirthCaptureType);
			$posAdditionalMemberChildMagstripeCaptureType = form_process_makesafe($posAdditionalMemberChildMagstripeCaptureType);
			$posAdditionalMemberChildPhotoCaptureType = form_process_makesafe($posAdditionalMemberChildPhotoCaptureType);

			$requestdata = [
			"name"             => $name,
			"membershipLengthType" => $membershipLengthType,
			"specificExpirationDate" => $specificExpirationDate,
			"membershipLength" => $membershipLength,
			"numberOfPeople" => $numberOfPeople,
			"memberTypes" => $memberTypes,
			"maximumAdults" => $maximumAdults,
			"maximumChildren" => $maximumChildren,
			"primaryMemberNameCaptureType" => $primaryMemberNameCaptureType,
			"primaryMemberDateOfBirthCaptureType" => $primaryMemberDateOfBirthCaptureType,
			"primaryMemberPhoneCaptureType" => $primaryMemberPhoneCaptureType,
			"primaryMemberEmailCaptureType" => $primaryMemberEmailCaptureType,
			"primaryMemberMarketingCaptureType" => $primaryMemberMarketingCaptureType,
			"posPrimaryMemberPhotoCaptureType" => $posPrimaryMemberPhotoCaptureType,
			"posPrimaryMemberMagstripeCaptureType" => $posPrimaryMemberMagstripeCaptureType,
			"additionalMemberAdultNameCaptureType" => $additionalMemberAdultNameCaptureType,
			"additionalMemberAdultDateOfBirthCaptureType" => $additionalMemberAdultDateOfBirthCaptureType,
			"additionalMemberAdultPhoneCaptureType" => $additionalMemberAdultPhoneCaptureType,
			"additionalMemberAdultEmailCaptureType" => $additionalMemberAdultEmailCaptureType,
			"additionalMemberAdultMarketingCaptureType" => $additionalMemberAdultMarketingCaptureType,
			"posAdditionalMemberAdultPhotoCaptureType" => $posAdditionalMemberAdultPhotoCaptureType,
			"posAdditionalMemberAdultMagstripeCaptureType" => $posAdditionalMemberAdultMagstripeCaptureType,
			"additionalMemberChildNameCaptureType" => $additionalMemberChildNameCaptureType,
			"additionalMemberChildDateOfBirthCaptureType" => $additionalMemberChildDateOfBirthCaptureType,
			"additionalMemberChildPhoneCaptureType" => $additionalMemberChildPhoneCaptureType,
			"additionalMemberChildEmailCaptureType" => $additionalMemberChildEmailCaptureType,
			"additionalMemberChildMarketingCaptureType" => $additionalMemberChildMarketingCaptureType,
			"posAdditionalMemberChildPhotoCaptureType" => $posAdditionalMemberChildPhotoCaptureType,
			"posAdditionalMemberChildMagstripeCaptureType" => $posAdditionalMemberChildMagstripeCaptureType,
			];

// API ACTION: UPDATE MEMBERSHIP

			$requesturl = "memberships/".$id;
			$requesttype = "PUT";
			$requestbody = $requestdata;

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:memberships-edit-additionalchild.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:memberships.php?pagenumber=$pagenumber&success=2");
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
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

$apierror = 0;

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
			$success = 'You have successfully edited this item';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>