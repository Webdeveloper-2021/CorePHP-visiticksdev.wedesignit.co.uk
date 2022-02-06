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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/memberships-edit-additionaladult.htm");

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

		if($additionalMemberAdultNameCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked0",'');
		}
		elseif($additionalMemberAdultNameCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultNameCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberAdultMarketingCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultMarketingCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultMarketingCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultMarketingCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultMarketingCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberAdultPhoneCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked0",'');
		}
		elseif($additionalMemberAdultPhoneCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultPhoneCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberAdultEmailCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked0",'');
		}
		elseif($additionalMemberAdultEmailCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultEmailCaptureTypechecked0",'checked="checked"');
		}

		if($additionalMemberAdultDateOfBirthCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked0",'');
		}
		elseif($additionalMemberAdultDateOfBirthCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("additionalMemberAdultDateOfBirthCaptureTypechecked0",'checked="checked"');
		}

		if($posAdditionalMemberAdultMagstripeCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked0",'');
		}
		elseif($posAdditionalMemberAdultMagstripeCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultMagstripeCaptureTypechecked0",'checked="checked"');
		}

		if($posAdditionalMemberAdultPhotoCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked0",'');
		}
		elseif($posAdditionalMemberAdultPhotoCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posAdditionalMemberAdultPhotoCaptureTypechecked0",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if(!$error)
			{
			$additionalMemberAdultNameCaptureType = stripslashes($_POST['additionalMemberAdultNameCaptureType']);
			$additionalMemberAdultMarketingCaptureType = stripslashes($_POST['additionalMemberAdultMarketingCaptureType']);
			$additionalMemberAdultPhoneCaptureType = stripslashes($_POST['additionalMemberAdultPhoneCaptureType']);
			$additionalMemberAdultEmailCaptureType = stripslashes($_POST['additionalMemberAdultEmailCaptureType']);
			$additionalMemberAdultDateOfBirthCaptureType = stripslashes($_POST['additionalMemberAdultDateOfBirthCaptureType']);
			$posAdditionalMemberAdultMagstripeCaptureType = stripslashes($_POST['posAdditionalMemberAdultMagstripeCaptureType']);
			$posAdditionalMemberAdultPhotoCaptureType = stripslashes($_POST['posAdditionalMemberAdultPhotoCaptureType']);

			$additionalMemberAdultNameCaptureType = form_process_makesafe($additionalMemberAdultNameCaptureType);
			$additionalMemberAdultMarketingCaptureType = form_process_makesafe($additionalMemberAdultMarketingCaptureType);
			$additionalMemberAdultPhoneCaptureType = form_process_makesafe($additionalMemberAdultPhoneCaptureType);
			$additionalMemberAdultEmailCaptureType = form_process_makesafe($additionalMemberAdultEmailCaptureType);
			$additionalMemberAdultDateOfBirthCaptureType = form_process_makesafe($additionalMemberAdultDateOfBirthCaptureType);
			$posAdditionalMemberAdultMagstripeCaptureType = form_process_makesafe($posAdditionalMemberAdultMagstripeCaptureType);
			$posAdditionalMemberAdultPhotoCaptureType = form_process_makesafe($posAdditionalMemberAdultPhotoCaptureType);

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