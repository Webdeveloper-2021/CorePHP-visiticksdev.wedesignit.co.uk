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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-content-emails-edit.htm");

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

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:settings-content-emails.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($id == "OrderConfirmation")
		{
		$emailtitle = 'Order Confirmation Email';
		$emailsubjectsetting = SETTING_EMAIL_ORDERCONFIRMATION_SUBJECT;
		$emailcontentsetting = SETTING_EMAIL_ORDERCONFIRMATION_BODY;
		$emaillinesetting = SETTING_EMAIL_ORDERCONFIRMATION_LINE;
		}
		elseif($id == "ResetPassword")
		{
		$emailtitle = 'Reset Password Email';
		$emailsubjectsetting = SETTING_EMAIL_RESETPASS_SUBJECT;
		$emailcontentsetting = SETTING_EMAIL_RESETPASS_BODY;
		$emaillinesetting = "";
		} 

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$emailcontent = stripslashes($_POST['emailcontent']);
		$emailsubject = stripslashes($_POST['emailsubject']);
		$emailline = stripslashes($_POST['emailline']);
		}
		else
		{
		$emailcontent = $emailcontentsetting;
		$emailsubject = $emailsubjectsetting;
		$emailline = $emaillinesetting;
		}

	$PHPTemplateLayer->assignGlobal("emailtitle",htmlspecialchars($emailtitle));
	$PHPTemplateLayer->assignGlobal("emailsubject",htmlspecialchars($emailsubject));
	$PHPTemplateLayer->assignGlobal("emailcontent",htmlspecialchars($emailcontent));

		if($emaillinesetting != "")
		{
		$PHPTemplateLayer->block("EMAILLINE");
		$PHPTemplateLayer->assign("emailline",htmlspecialchars($emailline));
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($emailsubject == "" || !form_valid_string('VARCHAR',$emailsubject))
			{
			$error = "1";
			$error1 = "1";
			}

			if($emailcontent == "" || !form_valid_string('TEXT',$emailcontent))
			{
			$error = "1";
			$error2 = "1";
			}

			if($emaillinesetting != "")
			{
				if($emailline == "" || !form_valid_string('TEXT',$emailline))
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if(!$error)
			{
			$emailsubject = form_process_makesafe($emailsubject);
			$emailcontent = $emailcontent;
			$emailline = $emailline;

// API ACTION: UPDATE SETTINGS

				if($id == "OrderConfirmation")
				{
					if($emailsubject != SETTING_EMAIL_ORDERCONFIRMATION_SUBJECT)
					{
						if(!UpdateSettings('OrderConfirmationEmailSubject',$emailsubject,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}

					if($emailcontent != SETTING_EMAIL_ORDERCONFIRMATION_BODY)
					{
						if(!UpdateSettings('OrderConfirmationEmailBody',$emailcontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}

					if($emailline != SETTING_EMAIL_ORDERCONFIRMATION_LINE)
					{
						if(!UpdateSettings('OrderConfirmationEmailLine',$emailline,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}
				elseif($id == "ResetPassword")
				{
					if($emailsubject != SETTING_EMAIL_RESETPASS_SUBJECT)
					{
						if(!UpdateSettings('ResetPasswordEmailSubject',$emailsubject,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}

					if($emailcontent != SETTING_EMAIL_RESETPASS_BODY)
					{
						if(!UpdateSettings('ResetPasswordEmailBody',$emailcontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

					if($_POST['saveedit'])
					{
					header("Location:settings-content-emails-edit.php?pagenumber=$pagenumber&success=1&id=$id");
					exit();
					}
					elseif($_POST['saveexit'])
					{
					header("Location:settings-content-emails.php?pagenumber=$pagenumber&success=2");
					exit();
					}
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("pagecontentclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("emailsubjectclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("emailcontentclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("emailcontentclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("emaillineclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("emaillineclass",'textbox-noerror');
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
			$error1 = 'Please enter a value in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter content';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter content';
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