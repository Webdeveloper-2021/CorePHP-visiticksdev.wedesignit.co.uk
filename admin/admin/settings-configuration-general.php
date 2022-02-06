<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "160";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-general.htm");

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

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:settings-configuration-general.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$AttractionName = stripslashes($_POST['AttractionName']);
		$TelephoneNumber = stripslashes($_POST['TelephoneNumber']);
		$CaptureTitlesWithNames = stripslashes($_POST['CaptureTitlesWithNames']);
		$CaptureTitlesWithMemberNames = stripslashes($_POST['CaptureTitlesWithMemberNames']);
		$CaptureMarketingAllowed = stripslashes($_POST['CaptureMarketingAllowed']);
		$NameMandatoryForCompanyAccount = stripslashes($_POST['NameMandatoryForCompanyAccount']);
		$ChildMaxAge = stripslashes($_POST['ChildMaxAge']);
		$MinimumMembershipRenewalDays = stripslashes($_POST['MinimumMembershipRenewalDays']);
		$BasketExpirationMinutes = stripslashes($_POST['BasketExpirationMinutes']);
		$CheckOutBasketExpirationAddMinutes = stripslashes($_POST['CheckOutBasketExpirationAddMinutes']);
		$IdealPostCodeAPIKey = stripslashes($_POST['IdealPostCodeAPIKey']);
		$LoggingEnabled = stripslashes($_POST['LoggingEnabled']);
		$GenericErrorMessage = stripslashes($_POST['GenericErrorMessage']);
		}
		else
		{
		$AttractionName = SETTING_TITLE;
		$TelephoneNumber = SETTING_TELEPHONE;
		$CaptureTitlesWithNames = SETTING_CAPTURETITLESWITHNAMES;
		$CaptureTitlesWithMemberNames = SETTING_CAPTURETITLESWITHMEMBERNAMES;
		$CaptureMarketingAllowed = SETTING_MARKETINGALLOWED;
		$NameMandatoryForCompanyAccount = SETTING_NAMEREQUIRED;
		$ChildMaxAge = SETTING_CHILDMAXAGE;
		$MinimumMembershipRenewalDays = SETTING_MIN_MEMBERSHIP_RENEWAL_DAYS;
		$BasketExpirationMinutes = SETTING_BASKETEXPIRATION;
		$CheckOutBasketExpirationAddMinutes = SETTING_CHECKOUTBASKETEXTEND;
		$IdealPostCodeAPIKey = INSTALL_POSTCODELOOKUPAPIKEY;
		$LoggingEnabled = SETTING_LOGS;
		$GenericErrorMessage = SETTING_GENERIC_ERROR_MSG;
		}

	$PHPTemplateLayer->assignGlobal("AttractionName",htmlspecialchars($AttractionName));
	$PHPTemplateLayer->assignGlobal("TelephoneNumber",htmlspecialchars($TelephoneNumber));
	$PHPTemplateLayer->assignGlobal("ChildMaxAge",htmlspecialchars($ChildMaxAge));
	$PHPTemplateLayer->assignGlobal("MinimumMembershipRenewalDays",htmlspecialchars($MinimumMembershipRenewalDays));
	$PHPTemplateLayer->assignGlobal("BasketExpirationMinutes",htmlspecialchars($BasketExpirationMinutes));
	$PHPTemplateLayer->assignGlobal("CheckOutBasketExpirationAddMinutes",htmlspecialchars($CheckOutBasketExpirationAddMinutes));
	$PHPTemplateLayer->assignGlobal("IdealPostCodeAPIKey",htmlspecialchars($IdealPostCodeAPIKey));
	$PHPTemplateLayer->assignGlobal("GenericErrorMessage",htmlspecialchars($GenericErrorMessage));

		if($CaptureTitlesWithNames == "1")
		{
		$PHPTemplateLayer->assignGlobal("CaptureTitlesWithNameschecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CaptureTitlesWithNameschecked",'');
		}

		if($CaptureTitlesWithMemberNames == "1")
		{
		$PHPTemplateLayer->assignGlobal("CaptureTitlesWithMemberNameschecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CaptureTitlesWithMemberNameschecked",'');
		}

		if($CaptureMarketingAllowed == "1")
		{
		$PHPTemplateLayer->assignGlobal("CaptureMarketingAllowedchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CaptureMarketingAllowedchecked",'');
		}

		if($NameMandatoryForCompanyAccount == "1")
		{
		$PHPTemplateLayer->assignGlobal("NameMandatoryForCompanyAccountchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("NameMandatoryForCompanyAccountchecked",'');
		}

		if($LoggingEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("LoggingEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("LoggingEnabledchecked",'');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($AttractionName == "" || !form_valid_string('VARCHAR',$AttractionName))
			{
			$error = "1";
			$error1 = "1";
			}

			if($TelephoneNumber == "" || !form_valid_string('VARCHAR',$TelephoneNumber))
			{
			$error = "1";
			$error2 = "1";
			}

			if($ChildMaxAge == "" || valid_Integer($ChildMaxAge,'VALUE','1','100') == "FALSE")
			{
			$error = "1";
			$error3 = "1";
			}

			if($MinimumMembershipRenewalDays == "" || valid_Integer($MinimumMembershipRenewalDays,'VALUE','1','1000') == "FALSE")
			{
			$error = "1";
			$error4 = "1";
			}

			if($BasketExpirationMinutes == "" || valid_Integer($BasketExpirationMinutes,'VALUE','1','100') == "FALSE")
			{
			$error = "1";
			$error5 = "1";
			}

			if($CheckOutBasketExpirationAddMinutes == "" || valid_Integer($CheckOutBasketExpirationAddMinutes,'VALUE','1','100') == "FALSE")
			{
			$error = "1";
			$error6 = "1";
			}

			if($IdealPostCodeAPIKey == "" || !form_valid_string('VARCHAR',$IdealPostCodeAPIKey))
			{
			$error = "1";
			$error7 = "1";
			}

			if($GenericErrorMessage == "" || !form_valid_string('VARCHAR',$GenericErrorMessage))
			{
			$error = "1";
			$error8 = "1";
			}

			if(!$error)
			{
			$AttractionName = form_process_makesafe($AttractionName);
			$TelephoneNumber = form_process_makesafe($TelephoneNumber);
			$CaptureTitlesWithNames = form_process_makesafe($CaptureTitlesWithNames);
			$CaptureTitlesWithMemberNames = form_process_makesafe($CaptureTitlesWithMemberNames);
			$CaptureMarketingAllowed = form_process_makesafe($CaptureMarketingAllowed);
			$NameMandatoryForCompanyAccount = form_process_makesafe($NameMandatoryForCompanyAccount);
			$ChildMaxAge = form_process_makesafe($ChildMaxAge);
			$MinimumMembershipRenewalDays = form_process_makesafe($MinimumMembershipRenewalDays);
			$BasketExpirationMinutes = form_process_makesafe($BasketExpirationMinutes);
			$CheckOutBasketExpirationAddMinutes = form_process_makesafe($CheckOutBasketExpirationAddMinutes);
			$IdealPostCodeAPIKey = form_process_makesafe($IdealPostCodeAPIKey);
			$LoggingEnabled = form_process_makesafe($LoggingEnabled);
			$GenericErrorMessage = form_process_makesafe($GenericErrorMessage);

// API ACTION: UPDATE SETTINGS

				if($AttractionName != SETTING_TITLE)
				{
					if(!UpdateSettings('AttractionName',$AttractionName,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($TelephoneNumber != SETTING_TELEPHONE)
				{
					if(!UpdateSettings('TelephoneNumber',$TelephoneNumber,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CaptureTitlesWithNames != SETTING_CAPTURETITLESWITHNAMES)
				{
					if(!UpdateSettings('CaptureTitlesWithNames',$CaptureTitlesWithNames,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CaptureTitlesWithMemberNames != SETTING_CAPTURETITLESWITHMEMBERNAMES)
				{
					if(!UpdateSettings('CaptureTitlesWithMemberNames',$CaptureTitlesWithMemberNames,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CaptureMarketingAllowed != SETTING_MARKETINGALLOWED)
				{
					if(!UpdateSettings('CaptureMarketingAllowed',$CaptureMarketingAllowed,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($NameMandatoryForCompanyAccount != SETTING_NAMEREQUIRED)
				{
					if(!UpdateSettings('NameMandatoryForCompanyAccount',$NameMandatoryForCompanyAccount,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($ChildMaxAge != SETTING_CHILDMAXAGE)
				{
					if(!UpdateSettings('ChildMaxAge',$ChildMaxAge,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($MinimumMembershipRenewalDays != SETTING_MIN_MEMBERSHIP_RENEWAL_DAYS)
				{
					if(!UpdateSettings('MinimumMembershipRenewalDays',$MinimumMembershipRenewalDays,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($BasketExpirationMinutes != SETTING_BASKETEXPIRATION)
				{
					if(!UpdateSettings('BasketExpirationMinutes',$BasketExpirationMinutes,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CheckOutBasketExpirationAddMinutes != SETTING_CHECKOUTBASKETEXTEND)
				{
					if(!UpdateSettings('CheckOutBasketExpirationAddMinutes',$CheckOutBasketExpirationAddMinutes,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($IdealPostCodeAPIKey != INSTALL_POSTCODELOOKUPAPIKEY)
				{
					if(!UpdateSettings('IdealPostCodeAPIKey',$IdealPostCodeAPIKey,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($LoggingEnabled != SETTING_LOGS)
				{
					if(!UpdateSettings('LoggingEnabled',$LoggingEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($GenericErrorMessage != SETTING_GENERIC_ERROR_MSG)
				{
					if(!UpdateSettings('GenericErrorMessage',$GenericErrorMessage,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-configuration-general.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("AttractionNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("AttractionNameclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("TelephoneNumberclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("TelephoneNumberclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("ChildMaxAgeclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("ChildMaxAgeclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("MinimumMembershipRenewalDaysclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("MinimumMembershipRenewalDaysclass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("BasketExpirationMinutesclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("BasketExpirationMinutesclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("CheckOutBasketExpirationAddMinutesclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CheckOutBasketExpirationAddMinutesclass",'textbox-noerror');
		}

		if($error7 != "")
		{
		$PHPTemplateLayer->assignGlobal("IdealPostCodeAPIKeyclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("IdealPostCodeAPIKeyclass",'textbox-noerror');
		}

		if($error8 != "")
		{
		$PHPTemplateLayer->assignGlobal("GenericErrorMessageclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("GenericErrorMessageclass",'textbox-noerror');
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
			$error1 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter a value between 1 and 100';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter a value between 1 and 1000';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please enter a value between 1 and 100';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

			if($error6 == "1")
			{
			$error6 = 'Please enter a value between 1 and 100';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);

			if($error7 == "1")
			{
			$error7 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error7",$error7);

			if($error8 == "1")
			{
			$error8 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error8",$error8);
		}
		elseif($success != "")
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'You have successfully updated your settings';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>