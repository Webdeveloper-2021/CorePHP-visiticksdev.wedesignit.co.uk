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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-branding-images.htm");

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
		header("Location:settings-branding-images.php");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

	$logoimage = SETTING_LOGO;
	$defaultimage = SETTING_DEFAULTIMAGE;

	$PHPTemplateLayer->assignGlobal("logopicture",$logoimage);
	$PHPTemplateLayer->assignGlobal("defaultpicture",$defaultimage);

		if($_POST['saveedit'])
		{
		$logoimagetype = stripslashes($_POST['logoimagetype']);
		$defaultimagetype = stripslashes($_POST['defaultimagetype']);

			if($logoimagetype == "NEW")
			{
			$PHPTemplateLayer->assignGlobal("logoimagetypechecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("logoimagetypechecked1",'checked="checked"');
			}

			if($defaultimagetype == "NEW")
			{
			$PHPTemplateLayer->assignGlobal("defaultimagetypechecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("defaultimagetypechecked1",'checked="checked"');
			}
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("logoimagetypechecked1",'checked="checked"');
		$PHPTemplateLayer->assignGlobal("defaultimagetypechecked1",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($_FILES['LogoFileName']['name'] != "" && $_FILES['LogoFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error1 = "1";
			}

			if(is_uploaded_file($_FILES['LogoFileName']['tmp_name']))
			{
				if($_FILES['LogoFileName']['error'] > 0) 
				{
				$error = "1";
				$error1 = "1";
				}

				if(process_uploadfilemimetype($_FILES["LogoFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error1 = "1";
				}

				if($_FILES['LogoFileName']['size'] > 2000000)
				{
				$error = "1";
				$error1 = "1";
				}
			}
			else
			{
				if($logoimagetype == "NEW")
				{
				$error = "1";
				$error1 = "1";
				}
			}

			if($_FILES['DefaultImageFileName']['name'] != "" && $_FILES['DefaultImageFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error2 = "1";
			}

			if(is_uploaded_file($_FILES['DefaultImageFileName']['tmp_name']))
			{
				if($_FILES['DefaultImageFileName']['error'] > 0) 
				{
				$error = "1";
				$error2 = "1";
				}

				if(process_uploadfilemimetype($_FILES["DefaultImageFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error2 = "1";
				}

				if($_FILES['DefaultImageFileName']['size'] > 2000000)
				{
				$error = "1";
				$error2 = "1";
				}
			}
			else
			{
				if($defaultimagetype == "NEW")
				{
				$error = "1";
				$error2 = "1";
				}
			}

			if(!$error)
			{
				if(is_uploaded_file($_FILES['LogoFileName']['tmp_name']))
				{
				$LogoFileName = process_uploadfilename($_FILES["LogoFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['LogoFileName']["tmp_name"],"../img/".$LogoFileName);
				}
				else
				{
				$LogoFileName = "";
				}

			$saveLogoFileName = ProcessEditImage($logoimagetype,SETTING_LOGO,$LogoFileName);

				if(is_uploaded_file($_FILES['DefaultImageFileName']['tmp_name']))
				{
				$DefaultFileName = process_uploadfilename($_FILES["DefaultImageFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['DefaultImageFileName']["tmp_name"],"../img/".$DefaultFileName);
				}
				else
				{
				$DefaultFileName = "";
				}

			$saveDefaultFileName = ProcessEditImage($defaultimagetype,SETTING_DEFAULTIMAGE,$DefaultFileName);

// API ACTION: UPDATE SETTINGS

				if($saveLogoFileName != SETTING_LOGO)
				{
					$LogoFileData = $_POST['LogoFileData'];

					if (($logoimagetype == "NEW" || $imagetype == "") && $LogoFileData !== "") {

						$imagerequestbody = [
							"originalFileName"     	=> $saveLogoFileName,
							"logo"		   			=> true,
							"imageData"				=> $LogoFileData,
						];

						$imagerequesturl = "";
						
						if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
							$apierror = 1;
						}
					}
				}

				if($saveDefaultFileName != SETTING_DEFAULTIMAGE)
				{
					$DefaultImageFileData = $_POST['DefaultImageFileData'];

					if (($defaultimagetype == "NEW" || $defaultimagetype == "") && $DefaultImageFileData !== "") {

						$imagerequestbody = [
							"originalFileName"     	=> $saveDefaultFileName,
							"DefaultCatalog"		=> true,
							"imageData"				=> $DefaultImageFileData,
						];

						$imagerequesturl = "";
						
						if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
							$apierror = 1;
						}
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
		$PHPTemplateLayer->assignGlobal("LogoFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("LogoFileNameclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("DefaultImageFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("DefaultImageFileNameclass",'textbox-noerror');
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
			$error1 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);
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