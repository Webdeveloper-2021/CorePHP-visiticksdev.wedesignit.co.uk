<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "LOGGEDOUT";
	$adminpermission = "";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/account-login.htm");

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

		if($_POST['submit'])
		{
		$email = htmlentities($_POST['email']); 
		$password = htmlentities($_POST['password']); 

		$PHPTemplateLayer->assignGlobal("email",$email);

		$email = form_process_makesafe($email);
		$password = form_process_makesafe($password);

$emailtot = "1";
$passwordtot = "1";

			if(!$email)
			{
			$error = "1";
			$error1 = "1";
			}
			elseif(!$emailtot)
			{
			$error = "1";
			$error1 = "2";
			}

			if(!$password)
			{
			$error = "1";
			$error2 = "1";
			}
			elseif(!$passwordtot)
			{
			$error = "1";
			$error2 = "2";
			}

			if(!$error)
			{

// LOGIN ACTIONS

			$requesturl = "users/admin/login";
			$requesttype = "POST";
			$requestbody = array('username'=>$email,'password'=>$password);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);

				if($requestresultarray[0] == "SUCCESS")
				{
					$adminUser = json_decode($requestresultarray[1], true);

					$requestpermissionurl = "users/admin/userpermissions/".$adminUser['id'];
					$requestpermissiontype = "GET";
					$requestpermissionbody = "";

					$requestpermissionresult = process_connect_Curl($U_SESSION_API_TOKEN,$requestpermissionurl,$requestpermissiontype,$requestpermissionbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

					if ($requestpermissionresult != "ERROR")
					{
					$requestpermissionresultarray = explode('|X|',$requestpermissionresult);

						if($requestpermissionresultarray[0] == "SUCCESS")
						{
						$adminuserpermissions = json_decode($requestpermissionresultarray[1]);
						
							$permissionList = array();

							foreach($adminuserpermissions as $permissionkey => $permissionobject)
							{
								$permissionList[] = $permissionobject->permissionId;
 							}

							$adminUser["permissions"] = $permissionList;
							$_SESSION["adminUser"] = $adminUser;

							header("Location:index.php");
							exit();
						} else {
							$$error = 1;
						}
					} else {
						$apierror = 1;
					}
				}
				else
				{
				$error = 1;
				}
			}
			else
			{
			$apierror = 1;
			}

			// header("Location:index.php");
			// exit();
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("emailclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("emailclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("passwordclass",'textbox-noerror');
		}

///*****************************************************************************///
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

// $apierror = 0;

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
			$error1 = 'Please enter your username';
			}
			elseif($error1 == "2")
			{
			$error1 = 'Your username was not recognized';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter your password';
			}
			elseif($error2 == "2")
			{
			$error2 = 'The password you entered was not correct';
			}
		}
		elseif($success)
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = "Your password has been updated and you can now login.";
			}

		$PHPTemplateLayer->assignGlobal("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>