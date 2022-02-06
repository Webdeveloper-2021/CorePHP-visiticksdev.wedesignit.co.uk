<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "ACCOUNT";
	$adminpermission = "";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/account-settings.htm");

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

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

	$fullname = $_SESSION['adminUser']['fullName'];

		if($_POST['submitdetails'])
		{
		$fullname = htmlentities($_POST['fullname']); 

			if(!$fullname || !form_valid_string('VARCHAR',$fullname))
			{
			$error = "1";
			$error4 = "1";
			}

		$fullname = form_process_makesafe($fullname);

			if(!$error)
			{

// UPDATE FULLNAME ACTIONS

				$requesturl = "users/admin/".$_SESSION['adminUser']['id'];
				$requesttype = "PUT";
				$requestbody = array('fullname'=>$fullname);

				$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						$_SESSION['adminUser']['fullName'] = $fullname;

						header("Location:account-settings.php?success=2");
						exit();
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

	$PHPTemplateLayer->assignGlobal("fullname",$fullname);

		if($_POST['submit'])
		{
		$password = htmlentities($_POST['password']); 
		$newpassword = htmlentities($_POST['newpassword']); 
		$newrepeatpassword = htmlentities($_POST['newrepeatpassword']); 

		$PHPTemplateLayer->assignGlobal("password",$password);
		$PHPTemplateLayer->assignGlobal("newpassword",$newpassword);
		$PHPTemplateLayer->assignGlobal("newrepeatpassword",$newrepeatpassword);

		$password = form_process_makesafe($password);
		$newpassword = form_process_makesafe($newpassword);
		$newrepeatpassword = form_process_makesafe($newrepeatpassword);

			if(!$newpassword || !form_valid_string('PASSWORD',$password))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$newrepeatpassword || $newrepeatpassword != $newpassword)
			{
			$error = "1";
			$error2 = "1";
			}

			if(!$password)
			{
			$error = "1";
			$error3 = "1";
			}

			if(!$error)
			{

// UPDATE PASSWORD ACTIONS

				$adminId = $_SESSION['adminUser']['id'];

				$requesturl = "users/admin/changepassword";
				$requesttype = "POST";
				$requestbody = array('userId'=>$adminId, 'currentPassword' => $password, "newPassword" => $newrepeatpassword);

				$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						header("Location:account-settings.php?success=1");
						exit();
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
		$PHPTemplateLayer->assignGlobal("newpasswordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("newpasswordclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("newrepeatpasswordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("newrepeatpasswordclass",'textbox-noerror');
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
		$PHPTemplateLayer->assignGlobal("fullnameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("fullnameclass",'textbox-noerror');
		}

///*****************************************************************************///
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

		if($error)
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
			$error1 = 'Please enter a valid password';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Your new password and repeated password do not match';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Your current password did not match our records.';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter your name.';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);
		}
		elseif($success)
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'Your password has been updated.';
			}
			elseif($success == "2")
			{
			$success = 'Your account details have been updated.';
			}

		$PHPTemplateLayer->assignGlobal("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>