<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "150";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	use includes\classes\controllers\UserRoleAdminController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/users-roles-edit.htm");

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
		header("Location:users-roles.php?pagenumber=$pagenumber");
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

	$user = new UserRoleAdminController();
	$user = $user->view($id);

	$name = $user->name;
	$userPermissions = $user->rolePermissions;

	$rolePermissions = array();

	foreach($userPermissions as $permissionKey => $permissionObj)
	{
		$rolePermissions[] = $permissionObj->permissionId;
	}

	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));

///*****************************************************************************///
///USER ROLES///
///*****************************************************************************///

	$permissionrequesturl = "permissions";
	$permissionrequesttype = "GET";
	$permissionrequestbody = "";

	$permissionrequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$permissionrequesturl,$permissionrequesttype,$permissionrequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($permissionrequestresult != "ERROR")
		{
		$permissionrequestresultarray = explode('|X|',$permissionrequestresult);
		$permissionrequestresult = json_decode($permissionrequestresultarray[1]);

			if($permissionrequestresultarray[0] != "FAIL")
			{
			$permissiontotalresults = count($permissionrequestresult);

				if($permissiontotalresults)
				{
				$PHPTemplateLayer->block("PERMISSIONS");

				$permissions = array();
					foreach($permissionrequestresult as $permissionkey => $permissionobject)
					{
					$PHPTemplateLayer->block("PERMISSION");

					$permissiontitle = $permissionobject->name;
					$permissionid = $permissionobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							// if($_POST["permissions".$discountingmembershipid] == "1")
							if($_POST["permissions".$permissionid] == "1")
							{
							$permissionchecked = 'checked="checked"';

							$permissions[] = $permissionid;
							}
							else
							{
							$permissionchecked = '';
							}
						}
						else
						{
							if(in_array($permissionid,$rolePermissions))
							{
							$permissionchecked = 'checked="checked"';
							}
							else
							{
							$permissionchecked = '';
							}
						}

					$PHPTemplateLayer->assign("permissionid",$permissionid);
					$PHPTemplateLayer->assign("permissiontitle",$permissiontitle);
					$PHPTemplateLayer->assign("permissionchecked",$permissionchecked);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOPERMISSIONS");
				}
			}
		}


		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		}

	$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($name));
	
		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);

		$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($named));

			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$permissions)
			{
			$error = "1";
			$error2 = "1";
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);
			
// API ACTION: UPDATE CATEGORY

			$requesturl = "roles/".$id;
			$requesttype = "PUT";
			$requestbody = array('name'=>$name,'permissions'=>$permissions);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:users-roles-edit.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:users-roles.php?pagenumber=$pagenumber&success=2");
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

			if($error2 == "1")
			{
			$error2 = 'Please select at least one set of permissions.';
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