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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-payments.htm");

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
		header("Location:settings-configuration-payments.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$OpayoVendorName = stripslashes($_POST['OpayoVendorName']);
		$PayPalClientID = stripslashes($_POST['PayPalClientID']);
		$PayPalClientSecret = stripslashes($_POST['PayPalClientSecret']);
		$SagePayEnabled = stripslashes($_POST['SagePayEnabled']);
		$OpayoTestMode = stripslashes($_POST['OpayoTestMode']);
		$PayPalEnabled = stripslashes($_POST['PayPalEnabled']);
		$PayPalEnvironment = stripslashes($_POST['PayPalEnvironment']);
		$ApplePayEnabled = stripslashes($_POST['ApplePayEnabled']);
		}
		else
		{
		$OpayoVendorName = OPAYO_VENDORNAME;
		$PayPalClientID = PAYPAL_CLIENT_ID;
		$PayPalClientSecret = PAYPAL_CLIENT_SECRET;
		$SagePayEnabled = OPAYO_ENABLED;
		$OpayoTestMode = OPAYO_TESTMODE;
		$PayPalEnabled = PAYPAL_ENABLED;
		$PayPalEnvironment = PAYPAL_ENVIRONMENT;
		$ApplePayEnabled = APPLE_ENABLED;
		}

	$PHPTemplateLayer->assignGlobal("OpayoVendorName",htmlspecialchars($OpayoVendorName));
	$PHPTemplateLayer->assignGlobal("PayPalClientID",htmlspecialchars($PayPalClientID));
	$PHPTemplateLayer->assignGlobal("PayPalClientSecret",htmlspecialchars($PayPalClientSecret));

		if($SagePayEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("SagePayEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("SagePayEnabledchecked",'');
		}

		if($OpayoTestMode == "1")
		{
		$PHPTemplateLayer->assignGlobal("OpayoTestModechecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("OpayoTestModechecked",'');
		}

		if($PayPalEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("PayPalEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("PayPalEnabledchecked",'');
		}

		if($PayPalEnvironment == "sandbox")
		{
		$PHPTemplateLayer->assignGlobal("PayPalEnvironmentchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("PayPalEnvironmentchecked",'');
		}

		if($ApplePayEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("ApplePayEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("ApplePayEnabledchecked",'');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($SagePayEnabled == "1")
			{
				if($OpayoVendorName == "" || !form_valid_string('VARCHAR',$OpayoVendorName))
				{
				$error = "1";
				$error1 = "1";
				}
			}

			if($PayPalEnabled == "1")
			{
				if($PayPalClientID == "" || !form_valid_string('VARCHAR',$PayPalClientID))
				{
				$error = "1";
				$error2 = "1";
				}

				if($PayPalClientSecret == "" || !form_valid_string('VARCHAR',$PayPalClientSecret))
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if(!$error)
			{
			$SagePayEnabled = form_process_makesafe($SagePayEnabled);
			$OpayoTestMode = form_process_makesafe($OpayoTestMode);
			$PayPalEnabled = form_process_makesafe($PayPalEnabled);
			$PayPalEnvironment = form_process_makesafe($PayPalEnvironment);
			$ApplePayEnabled = form_process_makesafe($ApplePayEnabled);

				if($SagePayEnabled)
				{
				$OpayoVendorName = form_process_makesafe($OpayoVendorName);
				}
				else
				{
				$OpayoVendorName = "";
				}

				if($PayPalEnabled)
				{
				$PayPalClientID = form_process_makesafe($PayPalClientID);
				$PayPalClientSecret = form_process_makesafe($PayPalClientSecret);
				}
				else
				{
				$PayPalClientID = "";
				$PayPalClientSecret = "";
				}

				if($PayPalEnvironment != "sandbox")
				{
				$PayPalEnvironment = "production";
				}

// API ACTION: UPDATE SETTINGS

				if($OpayoVendorName != OPAYO_VENDORNAME)
				{
					if(!UpdateSettings('OpayoVendorName',$OpayoVendorName,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($PayPalClientID != PAYPAL_CLIENT_ID)
				{
					if(!UpdateSettings('PayPalClientID',$PayPalClientID,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($PayPalClientSecret != PAYPAL_CLIENT_SECRET)
				{
					if(!UpdateSettings('PayPalClientSecret',$PayPalClientSecret,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($SagePayEnabled != OPAYO_ENABLED)
				{
					if(!UpdateSettings('SagePayEnabled',$SagePayEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($OpayoTestMode != OPAYO_TESTMODE)
				{
					if(!UpdateSettings('OpayoTestMode',$OpayoTestMode,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($PayPalEnabled != PAYPAL_ENABLED)
				{
					if(!UpdateSettings('PayPalEnabled',$PayPalEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($PayPalEnvironment != PAYPAL_ENVIRONMENT)
				{
					if(!UpdateSettings('PayPalEnvironment',$PayPalEnvironment,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($ApplePayEnabled != APPLE_ENABLED)
				{
					if(!UpdateSettings('ApplePayEnabled',$ApplePayEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-configuration-payments.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("OpayoVendorNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("OpayoVendorNameclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("PayPalClientIDclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("PayPalClientIDclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("PayPalClientSecretclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("PayPalClientSecretclass",'textbox-noerror');
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
			$error3 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);
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