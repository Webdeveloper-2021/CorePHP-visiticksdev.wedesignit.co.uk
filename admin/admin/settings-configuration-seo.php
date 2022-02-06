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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-seo.htm");

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
		header("Location:settings-configuration-seo.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$SEOIndexableEnabled = stripslashes($_POST['SEOIndexableEnabled']);
		$SEOMetaTitle = stripslashes($_POST['SEOMetaTitle']);
		$SEOMetaDescription = stripslashes($_POST['SEOMetaDescription']);
		$TrackingJavaScript = stripslashes($_POST['TrackingJavaScript']);
		$LeadConversionTrackingJavaScript = stripslashes($_POST['LeadConversionTrackingJavaScript']);
		}
		else
		{
		$SEOIndexableEnabled = SETTING_SEO_INDEXABLE;
		$SEOMetaTitle = SETTING_SEO_METATITLE;
		$SEOMetaDescription = SETTING_SEO_METADESCRIPTION;
		$TrackingJavaScript = SETTING_CODE_ALL;
		$LeadConversionTrackingJavaScript = SETTING_CODE_CONFIRMATION;
		}

	$PHPTemplateLayer->assignGlobal("SEOMetaTitle",htmlspecialchars($SEOMetaTitle));
	$PHPTemplateLayer->assignGlobal("SEOMetaDescription",htmlspecialchars($SEOMetaDescription));
	$PHPTemplateLayer->assignGlobal("TrackingJavaScript",htmlspecialchars($TrackingJavaScript));
	$PHPTemplateLayer->assignGlobal("LeadConversionTrackingJavaScript",htmlspecialchars($LeadConversionTrackingJavaScript));

		if($SEOIndexableEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("SEOIndexableEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("SEOIndexableEnabledchecked",'');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($SEOMetaTitle != "")
			{
				if(!form_valid_string('VARCHAR',$SEOMetaTitle))
				{
				$error = "1";
				$error1 = "1";
				}
			}

			if($SEOMetaDescription != "")
			{
				if(!form_valid_string('VARCHAR',$SEOMetaDescription))
				{
				$error = "1";
				$error2 = "1";
				}
			}

			if($TrackingJavaScript != "")
			{
				if(!form_valid_string('TEXT',$TrackingJavaScript))
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if($LeadConversionTrackingJavaScript != "")
			{
				if(!form_valid_string('VARCHAR',$LeadConversionTrackingJavaScript))
				{
				$error = "1";
				$error4 = "1";
				}
			}

			if(!$error)
			{
			$SEOIndexableEnabled = form_process_makesafe($SEOIndexableEnabled);
			$SEOMetaTitle = form_process_makesafe($SEOMetaTitle);
			$SEOMetaDescription = form_process_makesafe($SEOMetaDescription);
			$TrackingJavaScript = form_process_makesafe($TrackingJavaScript);
			$LeadConversionTrackingJavaScript = form_process_makesafe($LeadConversionTrackingJavaScript);

// API ACTION: UPDATE SETTINGS

				if($SEOIndexableEnabled != SETTING_SEO_INDEXABLE)
				{
					if(!UpdateSettings('SEOIndexableEnabled',$SEOIndexableEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($SEOMetaTitle != SETTING_SEO_METATITLE)
				{
					if(!UpdateSettings('SEOMetaTitle',$SEOMetaTitle,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($SEOMetaDescription != SETTING_SEO_METADESCRIPTION)
				{
					if(!UpdateSettings('SEOMetaDescription',$SEOMetaDescription,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($TrackingJavaScript != SETTING_CODE_ALL)
				{
					if(!UpdateSettings('TrackingJavaScript',$TrackingJavaScript,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($LeadConversionTrackingJavaScript != SETTING_CODE_CONFIRMATION)
				{
					if(!UpdateSettings('LeadConversionTrackingJavaScript',$LeadConversionTrackingJavaScript,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-configuration-seo.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("SEOMetaTitleclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("SEOMetaTitleclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("SEOMetaDescriptionclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("SEOMetaDescriptionclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("TrackingJavaScriptclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("TrackingJavaScriptclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("LeadConversionTrackingJavaScriptclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("LeadConversionTrackingJavaScriptclass",'textbox-noerror');
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
			$error1 = 'If entering a value, please limit to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'If entering a value, please limit to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'If entering a value, please limit to 80000 characters';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'If entering a value, please limit to 80000 characters';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);
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