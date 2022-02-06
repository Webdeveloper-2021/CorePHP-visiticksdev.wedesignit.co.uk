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

	use includes\classes\controllers\UserAdminController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/users-pass.htm");

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
		header("Location:users.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///GET CATEGORY DATA///
///*****************************************************************************///

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}


///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

// Get data for existing category

	$user = new UserAdminController();
	$user = $user->view($id);

	$fullName = $user->fullName;
	$currentpassword = $user->password;

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($fullName));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$password = stripslashes($_POST['password']);
		$repeatpassword = stripslashes($_POST['repeatpassword']);

		$PHPTemplateLayer->assignGlobal("password",htmlspecialchars($password));
		$PHPTemplateLayer->assignGlobal("repeatpassword",htmlspecialchars($repeatpassword));
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($password == "" || !form_valid_string('PASSWORD',$password))
			{
			$error = "1";
			$error1 = "1";
			}

			if($repeatpassword == "" || $repeatpassword != $password)
			{
			$error = "1";
			$error2 = "1";
			}

			if(!$error)
			{
			$password = form_process_makesafe($password);

// API ACTION: UPDATE CATEGORY

			$requesturl = "users/admin/resetpassword";
			$requesttype = "PUT";
			$requestbody = array('userId'=>$id,'newPassword'=>$password);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:users-pass.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:users.php?pagenumber=$pagenumber&success=4");
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
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-noerror');
		}

		if($error2 != "")
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

			if($error1== "1")
			{
			$error1 = 'Your new password does not contain the required combination of characters.';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Your password and repeated password do not match.';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);
		}
		elseif($success != "")
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'You have successfully edited this item';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>