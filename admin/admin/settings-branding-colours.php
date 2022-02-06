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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-branding-colours.htm");

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
		header("Location:settings-branding-colours.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$MainTitleColour = stripslashes($_POST['MainTitleColour']);
		$LinkColour = stripslashes($_POST['LinkColour']);
		$HeaderBackgroundColour = stripslashes($_POST['HeaderBackgroundColour']);
		$HeaderHoverColour = stripslashes($_POST['HeaderHoverColour']);
		$HeaderTextColour = stripslashes($_POST['HeaderTextColour']);
		$ButtonBackgroundColour = stripslashes($_POST['ButtonBackgroundColour']);
		$ButtonTextColour = stripslashes($_POST['ButtonTextColour']);
		$ButtonContrast = stripslashes($_POST['ButtonContrast']);
		$CalendarActiveColour = stripslashes($_POST['CalendarActiveColour']);
		$CalendarHoverColour = stripslashes($_POST['CalendarHoverColour']);
		$FooterTextColour = stripslashes($_POST['FooterTextColour']);
		$FooterBackgroundColour = stripslashes($_POST['FooterBackgroundColour']);
		}
		else
		{
		$MainTitleColour = CSS_TITLE;
		$LinkColour = CSS_LINKS;
		$HeaderBackgroundColour = CSS_HEADER_BG;
		$HeaderHoverColour = CSS_HEADER_HOVER;
		$HeaderTextColour = CSS_HEADER_TEXT;
		$ButtonBackgroundColour = CSS_BUTTON_BG;
		$ButtonTextColour = CSS_BUTTON_TEXT;
		$ButtonContrast = CSS_BUTTON_CONTRAST;
		$CalendarActiveColour = CSS_CALENDAR_ACTIVE;
		$CalendarHoverColour = CSS_CALENDAR_HOVER;
		$FooterTextColour = CSS_FOOTER_TEXT;
		$FooterBackgroundColour = CSS_FOOTER_BG;
		}

	$PHPTemplateLayer->assignGlobal("MainTitleColour",htmlspecialchars($MainTitleColour));
	$PHPTemplateLayer->assignGlobal("LinkColour",htmlspecialchars($LinkColour));
	$PHPTemplateLayer->assignGlobal("HeaderBackgroundColour",htmlspecialchars($HeaderBackgroundColour));
	$PHPTemplateLayer->assignGlobal("HeaderHoverColour",htmlspecialchars($HeaderHoverColour));
	$PHPTemplateLayer->assignGlobal("HeaderTextColour",htmlspecialchars($HeaderTextColour));
	$PHPTemplateLayer->assignGlobal("ButtonBackgroundColour",htmlspecialchars($ButtonBackgroundColour));
	$PHPTemplateLayer->assignGlobal("ButtonTextColour",htmlspecialchars($ButtonTextColour));
	$PHPTemplateLayer->assignGlobal("ButtonContrast",htmlspecialchars($ButtonContrast));
	$PHPTemplateLayer->assignGlobal("CalendarActiveColour",htmlspecialchars($CalendarActiveColour));
	$PHPTemplateLayer->assignGlobal("CalendarHoverColour",htmlspecialchars($CalendarHoverColour));
	$PHPTemplateLayer->assignGlobal("FooterTextColour",htmlspecialchars($FooterTextColour));
	$PHPTemplateLayer->assignGlobal("FooterBackgroundColour",htmlspecialchars($FooterBackgroundColour));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if(!$MainTitleColour || is_hex($MainTitleColour) == false)
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$LinkColour  || is_hex($LinkColour ) == false)
			{
			$error = "1";
			$error2 = "1";
			}

			if(!$HeaderBackgroundColour || is_hex($HeaderBackgroundColour) == false)
			{
			$error = "1";
			$error3 = "1";
			}

			if(!$HeaderHoverColour || is_hex($HeaderHoverColour) == false)
			{
			$error = "1";
			$error4 = "1";
			}

			if(!$HeaderTextColour || is_hex($HeaderTextColour) == false)
			{
			$error = "1";
			$error5 = "1";
			}

			if(!$ButtonBackgroundColour || is_hex($ButtonBackgroundColour) == false)
			{
			$error = "1";
			$error6 = "1";
			}

			if(!$ButtonTextColour || is_hex($ButtonTextColour) == false)
			{
			$error = "1";
			$error7 = "1";
			}

			if(!$ButtonContrast || is_hex($ButtonContrast) == false)
			{
			$error = "1";
			$error8 = "1";
			}

			if(!$CalendarActiveColour || is_hex($CalendarActiveColour) == false)
			{
			$error = "1";
			$error9 = "1";
			}

			if(!$CalendarHoverColour || is_hex($CalendarHoverColour) == false)
			{
			$error = "1";
			$error10 = "1";
			}

			if(!$FooterTextColour || is_hex($FooterTextColour) == false)
			{
			$error = "1";
			$error11 = "1";
			}

			if(!$FooterBackgroundColour || is_hex($FooterBackgroundColour) == false)
			{
			$error = "1";
			$error12 = "1";
			}

			if(!$error)
			{
			$MainTitleColour = form_process_makesafe($MainTitleColour);
			$LinkColour = form_process_makesafe($LinkColour);
			$HeaderBackgroundColour = form_process_makesafe($HeaderBackgroundColour);
			$HeaderHoverColour = form_process_makesafe($HeaderHoverColour);
			$HeaderTextColour = form_process_makesafe($HeaderTextColour);
			$ButtonBackgroundColour = form_process_makesafe($ButtonBackgroundColour);
			$ButtonTextColour = form_process_makesafe($ButtonTextColour);
			$ButtonContrast = form_process_makesafe($ButtonContrast);
			$CalendarActiveColour = form_process_makesafe($CalendarActiveColour);
			$CalendarHoverColour = form_process_makesafe($CalendarHoverColour);
			$FooterTextColour = form_process_makesafe($FooterTextColour);
			$FooterBackgroundColour = form_process_makesafe($FooterBackgroundColour);

// API ACTION: UPDATE SETTINGS

				if($MainTitleColour != CSS_TITLE)
				{
					if(!UpdateSettings('MainTitleColour',$MainTitleColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($LinkColour != CSS_LINKS)
				{
					if(!UpdateSettings('LinkColour',$LinkColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($HeaderBackgroundColour != CSS_HEADER_BG)
				{
					if(!UpdateSettings('HeaderBackgroundColour',$HeaderBackgroundColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($HeaderHoverColour != CSS_HEADER_HOVER)
				{
					if(!UpdateSettings('HeaderHoverColour',$HeaderHoverColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($HeaderTextColour != CSS_HEADER_TEXT)
				{
					if(!UpdateSettings('HeaderTextColour',$HeaderTextColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($ButtonBackgroundColour != CSS_BUTTON_BG)
				{
					if(!UpdateSettings('ButtonBackgroundColour',$ButtonBackgroundColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($ButtonTextColour != CSS_BUTTON_TEXT)
				{
					if(!UpdateSettings('ButtonTextColour',$ButtonTextColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($ButtonContrast != CSS_BUTTON_CONTRAST)
				{
					if(!UpdateSettings('ButtonContrast',$ButtonContrast,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CalendarActiveColour != CSS_CALENDAR_ACTIVE)
				{
					if(!UpdateSettings('CalendarActiveColour',$CalendarActiveColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CalendarHoverColour != CSS_CALENDAR_HOVER)
				{
					if(!UpdateSettings('CalendarHoverColour',$CalendarHoverColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($FooterTextColour != CSS_FOOTER_TEXT)
				{
					if(!UpdateSettings('FooterTextColour',$FooterTextColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($FooterBackgroundColour != CSS_FOOTER_BG)
				{
					if(!UpdateSettings('FooterBackgroundColour',$FooterBackgroundColour,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-branding-colours.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("MainTitleColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("MainTitleColourclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("LinkColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("LinkColourclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("HeaderBackgroundColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HeaderBackgroundColourclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("HeaderHoverColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HeaderHoverColourclass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("HeaderTextColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HeaderTextColourclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("ButtonBackgroundColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("ButtonBackgroundColourclass",'textbox-noerror');
		}

		if($error7 != "")
		{
		$PHPTemplateLayer->assignGlobal("ButtonTextColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("ButtonTextColourclass",'textbox-noerror');
		}

		if($error8 != "")
		{
		$PHPTemplateLayer->assignGlobal("ButtonContrastclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("ButtonContrastclass",'textbox-noerror');
		}

		if($error9 != "")
		{
		$PHPTemplateLayer->assignGlobal("CalendarActiveColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CalendarActiveColourclass",'textbox-noerror');
		}

		if($error10 != "")
		{
		$PHPTemplateLayer->assignGlobal("CalendarHoverColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CalendarHoverColourclass",'textbox-noerror');
		}

		if($error11 != "")
		{
		$PHPTemplateLayer->assignGlobal("FooterTextColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("FooterTextColourclass",'textbox-noerror');
		}

		if($error12 != "")
		{
		$PHPTemplateLayer->assignGlobal("FooterBackgroundColourclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("FooterBackgroundColourclass",'textbox-noerror');
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
			$error1 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

			if($error6 == "1")
			{
			$error6 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);

			if($error7 == "1")
			{
			$error7 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error7",$error7);

			if($error8 == "1")
			{
			$error8 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error8",$error8);

			if($error9 == "1")
			{
			$error9 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error9",$error9);

			if($error10 == "1")
			{
			$error10 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error10",$error10);

			if($error11 == "1")
			{
			$error11 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error11",$error11);

			if($error12 == "1")
			{
			$error12 = 'Please enter a valid colour code';
			}

		$PHPTemplateLayer->assignGlobal("error12",$error12);
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