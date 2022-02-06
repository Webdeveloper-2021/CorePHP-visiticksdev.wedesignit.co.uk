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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-branding-fonts.htm");

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
		header("Location:settings-branding-fonts.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$MainTitleFont = stripslashes($_POST['MainTitleFont']);

			if($MainTitleFont == "1")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "2")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "3")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "4")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "5")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "6")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == "7")
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
		}
		else
		{
		$MainTitleFont = CSS_FONT;

			if($MainTitleFont == '"Open Sans", san-serif')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"Patua One", serif')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"EB Garamond", serif')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"Luckiest Guy", cursive')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"Fredoka One", cursive')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"Great Vibes", cursive')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
			elseif($MainTitleFont == '"Coming Soon", fantasy')
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked1",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked2",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked3",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked4",'checked="checked"');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked5",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked6",'');
			$PHPTemplateLayer->assignGlobal("MainTitleFontchecked7",'');
			}
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if(!$error)
			{
			$MainTitleFont = form_process_makesafe($MainTitleFont);

				if($MainTitleFont == "1")
				{
				$MainTitleFont = '"Open Sans", san-serif';
				}
				elseif($MainTitleFont == "2")
				{
				$MainTitleFont = '"Patua One", serif';
				}
				elseif($MainTitleFont == "3")
				{
				$MainTitleFont = '"EB Garamond", serif';
				}
				elseif($MainTitleFont == "4")
				{
				$MainTitleFont = '"Luckiest Guy", cursive';
				}
				elseif($MainTitleFont == "5")
				{
				$MainTitleFont = '"Fredoka One", cursive';
				}
				elseif($MainTitleFont == "6")
				{
				$MainTitleFont = '"Great Vibes", cursive';
				}
				elseif($MainTitleFont == "7")
				{
				$MainTitleFont = '"Coming Soon", fantasy';
				}
				else
				{
				$MainTitleFont = '"Luckiest Guy", cursive';
				}

// API ACTION: UPDATE SETTINGS

				if($MainTitleFont != CSS_FONT)
				{
					if(!UpdateSettings('MainTitleFont',$MainTitleFont,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-branding-fonts.php?success=1");
				exit();
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
			$success = 'You have successfully updated your settings';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>