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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/users-edit.htm");

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
	$userRoles = array();

	$userrolerequesturl = "users/admin/userroles/".$id;
	$userrolerequesttype = "GET";
	$userrolerequestbody = "";

	$userrolerequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$userrolerequesturl,$userrolerequesttype,$userrolerequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($userrolerequestresult != "ERROR")
		{
			$userrolerequestresultarray = explode('|X|',$userrolerequestresult);
			$userrolerequestresult = json_decode($userrolerequestresultarray[1]);

			if($rolerequestresultarray[0] != "FAIL")
			{
				$userRolesList = json_decode($userrolerequestresultarray[1]);
				foreach($userRolesList as $userKey => $userObj)
				{
					$userRoles[] = $userObj->roleId;
				}
			}
		}



	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($fullName));

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

					foreach($rolerequestresult as $rolekey => $roleobject)
					{
					$PHPTemplateLayer->block("ROLE");

					$roletitle = $roleobject->name;
					$roleid = $roleobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							// if($_POST["userRoles".$discountingmembershipid] == "1")
							if($_POST["userRoles".$roleid] == "1")
							{
							$userrolechecked = 'checked="checked"';

							$userRoles[] = $roleid;
							}
							else
							{
							$userrolechecked = '';
							}
						}
						else
						{
							if(in_array($roleid,$userRoles))
							{
							$userrolechecked = 'checked="checked"';
							}
							else
							{
							$userrolechecked = '';
							}
						}

						// if($_POST["userRoles".$roleid] == "1")
						// {
						// $userrolechecked = 'checked="checked"';

						// $userRoles[] = $roleid;
						// }
						// else
						// {
						// $userrolechecked = '';
						// }

					$PHPTemplateLayer->assign("userroleid",$roleid);
					$PHPTemplateLayer->assign("userroletitle",$roletitle);
					$PHPTemplateLayer->assign("userrolechecked",$userrolechecked);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOROLES");
				}
			}
		}


		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$fullName = stripslashes($_POST['fullName']);
		}

	$PHPTemplateLayer->assignGlobal("fullName",htmlspecialchars($fullName));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($fullName == "" || !form_valid_string('VARCHAR',$fullName))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$error)
			{
			$fullName = form_process_makesafe($fullName);

// API ACTION: UPDATE CATEGORY

			$requesturl = "users/admin/".$id;
			$requesttype = "PUT";
			$requestbody = array('fullName'=>$fullName,'userRoles'=>$userRoles);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:users-edit.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:users.php?pagenumber=$pagenumber&success=2");
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
			$error2 = 'Please select at least one role for this user.';
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