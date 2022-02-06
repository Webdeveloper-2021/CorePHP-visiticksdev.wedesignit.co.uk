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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/memberships-edit-primarymember.htm");

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

		if($memberTypes == "1")
		{
		$PHPTemplateLayer->block("MIXEDTABS");
		}

		if($memberTypes == "1")
		{
		$PHPTemplateLayer->assignGlobal("mixedbutton",' &amp; Continue &gt;');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("mixedbutton",'');
		}

		if($primaryMemberNameCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked0",'');
		}
		elseif($primaryMemberNameCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberNameCaptureTypechecked0",'checked="checked"');
		}

		if($primaryMemberMarketingCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberMarketingCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberMarketingCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberMarketingCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberMarketingCaptureTypechecked0",'checked="checked"');
		}

		if($primaryMemberPhoneCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked0",'');
		}
		elseif($primaryMemberPhoneCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberPhoneCaptureTypechecked0",'checked="checked"');
		}

		if($primaryMemberEmailCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked0",'');
		}
		elseif($primaryMemberEmailCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberEmailCaptureTypechecked0",'checked="checked"');
		}

		if($primaryMemberDateOfBirthCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked0",'');
		}
		elseif($primaryMemberDateOfBirthCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("primaryMemberDateOfBirthCaptureTypechecked0",'checked="checked"');
		}

		if($posPrimaryMemberMagstripeCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked0",'');
		}
		elseif($posPrimaryMemberMagstripeCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberMagstripeCaptureTypechecked0",'checked="checked"');
		}

		if($posPrimaryMemberPhotoCaptureType == "1")
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked0",'');
		}
		elseif($posPrimaryMemberPhotoCaptureType == "2")
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked2",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked0",'');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked1",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked2",'');
		$PHPTemplateLayer->assignGlobal("posPrimaryMemberPhotoCaptureTypechecked0",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if(!$error)
			{
			$primaryMemberNameCaptureType = stripslashes($_POST['primaryMemberNameCaptureType']);
			$primaryMemberMarketingCaptureType = stripslashes($_POST['primaryMemberMarketingCaptureType']);
			$primaryMemberPhoneCaptureType = stripslashes($_POST['primaryMemberPhoneCaptureType']);
			$primaryMemberEmailCaptureType = stripslashes($_POST['primaryMemberEmailCaptureType']);
			$primaryMemberDateOfBirthCaptureType = stripslashes($_POST['primaryMemberDateOfBirthCaptureType']);
			$posPrimaryMemberMagstripeCaptureType = stripslashes($_POST['posPrimaryMemberMagstripeCaptureType']);
			$posPrimaryMemberPhotoCaptureType = stripslashes($_POST['posPrimaryMemberPhotoCaptureType']);

			$primaryMemberNameCaptureType = form_process_makesafe($primaryMemberNameCaptureType);
			$primaryMemberMarketingCaptureType = form_process_makesafe($primaryMemberMarketingCaptureType);
			$primaryMemberPhoneCaptureType = form_process_makesafe($primaryMemberPhoneCaptureType);
			$primaryMemberEmailCaptureType = form_process_makesafe($primaryMemberEmailCaptureType);
			$primaryMemberDateOfBirthCaptureType = form_process_makesafe($primaryMemberDateOfBirthCaptureType);
			$posPrimaryMemberMagstripeCaptureType = form_process_makesafe($posPrimaryMemberMagstripeCaptureType);
			$posPrimaryMemberPhotoCaptureType = form_process_makesafe($posPrimaryMemberPhotoCaptureType);

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
							if($memberTypes == "1")
							{
							header("Location:memberships-edit-additionaladult.php?pagenumber=$pagenumber&success=1&id=$id");
							exit();
							}
							else
							{
							header("Location:memberships-edit-primarymember.php?pagenumber=$pagenumber&success=1&id=$id");
							exit();
							}
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