<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "140";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/users-add.htm");

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
		header("Location:users.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///USER ROLES///
///*****************************************************************************///

	$rolerequesturl = "roles";
	$rolerequesttype = "GET";
	$rolerequestbody = "";

	$rolerequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$rolerequesturl,$rolerequesttype,$rolerequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($rolerequestresult != "ERROR")
		{
		$rolerequestresultarray = explode('|X|',$rolerequestresult);
		$rolerequestresult = json_decode($rolerequestresultarray[1]);

			if($rolerequestresultarray[0] != "FAIL")
			{
			$roletotalresults = count($rolerequestresult);

				if($roletotalresults)
				{
				$PHPTemplateLayer->block("ROLES");

				$userRoles = array();

					foreach($rolerequestresult as $rolekey => $roleobject)
					{
					$PHPTemplateLayer->block("ROLE");

					$roletitle = $roleobject->name;
					$roleid = $roleobject->id;

						if($_POST["userRoles".$roleid] == "1")
						{
						$rolechecked = 'checked="checked"';

						$userRoles[] = $roleid;
						}
						else
						{
						$rolechecked = '';
						}

					$PHPTemplateLayer->assign("userroleid",$roleid);
					$PHPTemplateLayer->assign("userroletitle",$roletitle);
					$PHPTemplateLayer->assign("userrolechecked",$rolechecked);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOROLES");
				}
			}
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$fullName = stripslashes($_POST['fullName']);
		$userName = stripslashes($_POST['userName']);
		$password = stripslashes($_POST['password']);
		$repeatpassword = stripslashes($_POST['repeatpassword']);

		$PHPTemplateLayer->assignGlobal("fullName",htmlspecialchars($fullName));
		$PHPTemplateLayer->assignGlobal("userName",htmlspecialchars($userName));
		$PHPTemplateLayer->assignGlobal("password",htmlspecialchars($password));
		$PHPTemplateLayer->assignGlobal("repeatpassword",htmlspecialchars($repeatpassword));

			if($fullName == "" || !form_valid_string('VARCHAR',$fullName))
			{
			$error = "1";
			$error1 = "1";
			}

			if($userName == "" || !form_valid_string('USERNAME',$userName))
			{
			$error = "1";
			$error2 = "1";
			}

			if($password == "" || !form_valid_string('PASSWORD',$password))
			{
			$error = "1";
			$error3 = "1";
			}

			if($repeatpassword == "" || $repeatpassword != $password)
			{
			$error = "1";
			$error4 = "1";
			}

			if(!$userRoles)
			{
			$error = "1";
			$error5 = "1";
			}

			if(!$error)
			{
			$fullName = form_process_makesafe($fullName);
			$userName = form_process_makesafe($userName);
			$password = form_process_makesafe($password);

// API ACTION: CREATE CATEGORY

			$requestdata = [
			"fullName"             => $fullName,
			"userName"             => $userName,
			"password"             => $password,
			"userRoles"             => $userRoles,
			];

			$requesturl = "users/admin";
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
						header("Location:users-edit.php?pagenumber=$pagenumber&success=1&id=$newid");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:users.php?pagenumber=$pagenumber&success=1");
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
		$PHPTemplateLayer->assignGlobal("fullNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("fullNameclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("userNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("userNameclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("repeatpasswordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("repeatpasswordclass",'textbox-noerror');
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

			if($error2 == "1")
			{
			$error2 = 'Your username does not contain the required combination of characters.';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Your new password does not contain the required combination of characters.';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Your password and repeated password do not match.';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please select at least one role for this user.';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>