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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-content-home.htm");

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
		header("Location:settings-content-home.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

	$categoryimage = SETTING_HOME_CATEGORY_IMAGE;
	$categorypicture = DefaultImage($categoryimage,SETTING_DEFAULTIMAGE);

	$eventimage = SETTING_HOME_EVENT_IMAGE;
	$eventpicture = DefaultImage($eventimage,SETTING_DEFAULTIMAGE);

		if($_POST['saveedit'])
		{
		$HomeCategoryButtonText = stripslashes($_POST['HomeCategoryButtonText']);
		$HomeCategoryText = stripslashes($_POST['HomeCategoryText']);
		$HomeEventButtonText = stripslashes($_POST['HomeEventButtonText']);
		$HomeEventText = stripslashes($_POST['HomeEventText']);
		$CategoriesTabText = stripslashes($_POST['CategoriesTabText']);
		$CalendarTabText = stripslashes($_POST['CalendarTabText']);
		$categoryimagetype = stripslashes($_POST['categoryimagetype']);
		$eventimagetype = stripslashes($_POST['eventimagetype']);

			if($categoryimagetype == "EXISTING")
			{
			$PHPTemplateLayer->assignGlobal("categoryimagetypechecked1",'checked="checked"');
			}
			elseif($categoryimagetype == "NEW")
			{
			$PHPTemplateLayer->assignGlobal("categoryimagetypechecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("categoryimagetypechecked3",'checked="checked"');
			}

			if($eventimagetype == "EXISTING")
			{
			$PHPTemplateLayer->assignGlobal("eventimagetypechecked1",'checked="checked"');
			}
			elseif($eventimagetype == "NEW")
			{
			$PHPTemplateLayer->assignGlobal("eventimagetypechecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("eventimagetypechecked3",'checked="checked"');
			}
		}
		else
		{
		$HomeCategoryButtonText = SETTING_HOME_CATEGORY_BUTTON;
		$HomeCategoryText = SETTING_HOME_CATEGORY_TEXT;
		$HomeEventButtonText = SETTING_HOME_EVENT_BUTTON;
		$HomeEventText = SETTING_HOME_EVENT_TEXT;
		$CategoriesTabText = SETTING_TAB_CATEGORIES;
		$CalendarTabText = SETTING_TAB_CALENDAR;
		}

		if($categoryimage != "")
		{
		$PHPTemplateLayer->block("CATEGORYIMG");
		$PHPTemplateLayer->assign("categorypicture",$categorypicture);

		$PHPTemplateLayer->assign("categoryimagetypechecked1",'checked="checked"');
		}

		if($eventimage != "")
		{
		$PHPTemplateLayer->block("EVENTIMG");
		$PHPTemplateLayer->assign("eventpicture",$eventpicture);

		$PHPTemplateLayer->assign("eventimagetypechecked1",'checked="checked"');
		}

	$PHPTemplateLayer->assignGlobal("HomeCategoryButtonText",htmlspecialchars($HomeCategoryButtonText));
	$PHPTemplateLayer->assignGlobal("HomeCategoryText",$HomeCategoryText);
	$PHPTemplateLayer->assignGlobal("HomeEventButtonText",htmlspecialchars($HomeEventButtonText));
	$PHPTemplateLayer->assignGlobal("HomeEventText",$HomeEventText);
	$PHPTemplateLayer->assignGlobal("CategoriesTabText",htmlspecialchars($CategoriesTabText));
	$PHPTemplateLayer->assignGlobal("CalendarTabText",htmlspecialchars($CalendarTabText));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($HomeCategoryButtonText == "" || !form_valid_string('VARCHAR',$HomeCategoryButtonText))
			{
			$error = "1";
			$error1 = "1";
			}

			if($HomeCategoryText == "" || !form_valid_string('VARCHAR',$HomeCategoryText))
			{
			$error = "1";
			$error2 = "1";
			}

			if($_FILES['HomeCategoryImageFileName']['name'] != "" && $_FILES['HomeCategoryImageFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error3 = "1";
			}

			if(is_uploaded_file($_FILES['HomeCategoryImageFileName']['tmp_name']))
			{
				if($_FILES['HomeCategoryImageFileName']['error'] > 0) 
				{
				$error = "1";
				$error3 = "1";
				}

				if(process_uploadfilemimetype($_FILES["HomeCategoryImageFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error3 = "1";
				}

				if($_FILES['HomeCategoryImageFileName']['size'] > 2000000)
				{
				$error = "1";
				$error3 = "1";
				}
			}
			else
			{
				if($categoryimagetype == "NEW")
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if($HomeEventButtonText == "" || !form_valid_string('VARCHAR',$HomeEventButtonText))
			{
			$error = "1";
			$error4 = "1";
			}

			if($HomeEventText == "" || !form_valid_string('VARCHAR',$HomeEventText))
			{
			$error = "1";
			$error5 = "1";
			}

			if($_FILES['HomeEventImageFileName']['name'] != "" && $_FILES['HomeEventImageFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error6 = "1";
			}

			if(is_uploaded_file($_FILES['HomeEventImageFileName']['tmp_name']))
			{
				if($_FILES['HomeEventImageFileName']['error'] > 0) 
				{
				$error = "1";
				$error6 = "1";
				}

				if(process_uploadfilemimetype($_FILES["HomeEventImageFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error6 = "1";
				}

				if($_FILES['HomeEventImageFileName']['size'] > 2000000)
				{
				$error = "1";
				$error6 = "1";
				}
			}
			else
			{
				if($eventimagetype == "NEW")
				{
				$error = "1";
				$error6 = "1";
				}
			}

			if($CategoriesTabText == "" || !form_valid_string('VARCHAR',$CategoriesTabText))
			{
			$error = "1";
			$error7 = "1";
			}

			if($CalendarTabText == "" || !form_valid_string('VARCHAR',$CalendarTabText))
			{
			$error = "1";
			$error8 = "1";
			}

			if(!$error)
			{
			$HomeCategoryButtonText = form_process_makesafe($HomeCategoryButtonText);
			$HomeCategoryText = form_process_WYSIWYG($HomeCategoryText);
			$HomeEventButtonText = form_process_makesafe($HomeEventButtonText);
			$HomeEventText = form_process_WYSIWYG($HomeEventText);
			$CategoriesTabText = form_process_makesafe($CategoriesTabText);
			$CalendarTabText = form_process_makesafe($CalendarTabText);

				if(is_uploaded_file($_FILES['HomeCategoryImageFileName']['tmp_name']))
				{
				$HomeCategoryImageFileName = process_uploadfilename($_FILES["HomeCategoryImageFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['HomeCategoryImageFileName']["tmp_name"],"../img/".$HomeCategoryImageFileName);
				}
				else
				{
				$HomeCategoryImageFileName = "";
				}

			$saveHomeCategoryImageFileName = ProcessEditImage($categoryimagetype,SETTING_HOME_CATEGORY_IMAGE,$HomeCategoryImageFileName);

				if(is_uploaded_file($_FILES['HomeEventImageFileName']['tmp_name']))
				{
				$HomeEventImageFileName = process_uploadfilename($_FILES["HomeEventImageFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['HomeEventImageFileName']["tmp_name"],"../img/".$HomeEventImageFileName);
				}
				else
				{
				$HomeEventImageFileName = "";
				}

			$saveHomeEventImageFileName = ProcessEditImage($eventimagetype,SETTING_HOME_EVENT_IMAGE,$HomeEventImageFileName);

// API ACTION: UPDATE SETTINGS


				$HomeCategoryImageFileData = $_POST['HomeCategoryImageFileData'];

				if (($categoryimagetype == "NEW" || $categoryimagetype == "") && $imageFileData !== "") {

					$imagerequestbody = [
						"originalFileName"     	=> $saveHomeCategoryImageFileName,
						"HomeCategories"		=> true,
						"imageData"				=> $HomeCategoryImageFileData,
					];

					$imagerequesturl = "";
					
					if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
						$apierror = 1;
					}
				}

				if ($categoryimagetype == "NONE") {
					$imagerequestbody = [
						"HomeCategories"		=> true,
					];

					$imagerequesturl = "clearimage";
					
					if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
						$apierror = 1;
					}
				}




				$HomeEventImageFileData = $_POST['HomeEventImageFileData'];

				if (($eventimagetype == "NEW" || $eventimagetype == "") && $HomeEventImageFileData !== "") {

					$imagerequestbody = [
						"originalFileName"     	=> $saveHomeEventImageFileName,
						"HomeCalendar"			=> true,
						"imageData"				=> $HomeEventImageFileData,
					];

					$imagerequesturl = "";
					
					if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
						$apierror = 1;
					}
				}

				if ($eventimagetype == "NONE") {
					$imagerequestbody = [
						"HomeCalendar"			=> true,
					];

					$imagerequesturl = "clearimage";
					
					if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
						$apierror = 1;
					}
				}




				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-content-home.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryButtonTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryButtonTextclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryTextclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryImageFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeCategoryImageFileNameclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeEventButtonTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeEventButtonTextlass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeEventTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeEventTextclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("HomeEventImageFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("HomeEventImageFileNameclass",'textbox-noerror');
		}

		if($error7 != "")
		{
		$PHPTemplateLayer->assignGlobal("CategoriesTabTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CategoriesTabTextclass",'textbox-noerror');
		}

		if($error8 != "")
		{
		$PHPTemplateLayer->assignGlobal("CalendarTabTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CalendarTabTextclass",'textbox-noerror');
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
			$error2 = 'Please enter a value in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter a value in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please enter a value in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

			if($error6 == "1")
			{
			$error6 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);

			if($error7 == "1")
			{
			$error7 = 'Please enter a value in less than 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error7",$error7);

			if($error8 == "1")
			{
			$error8 = 'Please enter a value in less than 250 characters';
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