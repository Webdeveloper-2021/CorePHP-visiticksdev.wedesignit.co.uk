<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "70";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-reporting-groups-add.htm");

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

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:settings-reporting-groups.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);

		$PHPTemplateLayer->assign("name",htmlspecialchars($name));

			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);

// API ACTION: CREATE CATEGORY

			$requestdata = [
			"name"             => $name,
			];

			$requesturl = "reportcategorygroups";
			$requesttype = "POST";
			$requestbody = $requestdata;

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

				$requestresult = json_decode($requestresultarray[1],'true');

					if($requestresultarray[0] == "SUCCESS")
					{
					$newid = $requestresult["id"];

						if($_POST['saveedit'])
						{
						header("Location:settings-reporting-groups-edit.php?pagenumber=$pagenumber&success=1&id=$newid");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:settings-reporting-groups.php?pagenumber=$pagenumber&success=1");
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
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("nameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("nameclass",'textbox-noerror');
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
			$error1 = 'Please enter a name';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>