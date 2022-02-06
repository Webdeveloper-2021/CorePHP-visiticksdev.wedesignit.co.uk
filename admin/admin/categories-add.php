<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "40";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/categories-add.htm");

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
		header("Location:categories.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if($_POST['status'] && $_POST['status'] == "HIDDEN")
		{
		$PHPTemplateLayer->assign("statuschecked2",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assign("statuschecked1",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		$description = stripslashes($_POST['description']);
		$status = stripslashes($_POST['status']);

		$PHPTemplateLayer->assign("name",htmlspecialchars($name));
		$PHPTemplateLayer->assign("description",htmlspecialchars($description));

			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}

			if($description == "")
			{
			$error = "1";
			$error2 = "1";
			}
			elseif(!form_valid_string('VARCHAR',$description))
			{
			$error = "1";
			$error2 = "2";
			}

			if($_FILES['imageFileName']['name'] != "" && $_FILES['imageFileName']['error'] != UPLOAD_ERR_OK)
			{
			$error = "1";
			$error3 = "1";
			}

			if(is_uploaded_file($_FILES['imageFileName']['tmp_name']))
			{
				if($_FILES['imageFileName']['error'] > 0) 
				{
				$error = "1";
				$error3 = "1";
				}

				if(process_uploadfilemimetype($_FILES["imageFileName"]["type"],'IMAGE') == "FALSE")
				{
				$error = "1";
				$error3 = "1";
				}

				if($_FILES['imageFileName']['size'] > 2000000)
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);
			$description = form_process_WYSIWYG($description);

				if($status == "VISIBLE")
				{
				$visible = 1;
				}
				else
				{
				$visible = 0;
				}

				if(is_uploaded_file($_FILES['imageFileName']['tmp_name']))
				{
				$imageFileName = process_uploadfilename($_FILES["imageFileName"]["name"],'TRUE');

				// move_uploaded_file($_FILES['imageFileName']["tmp_name"],"../img/".$imageFileName);
				}
				else
				{
				$imageFileName = "";
				}

// API ACTION: CREATE CATEGORY

			$requestdata = [
			"name"             => $name,
			"description"             => $description,
			"visible"             => $visible,
			"imageFileName"             => $imageFileName,
			];

			$requesturl = "categories";
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

						if(!CategoriesSetDisplayOrder($U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
							$apierror = 1;
						}

						$imageFileData = $_POST['imageFileData'];

						$imagerequestbody = [
							"originalFileName"     	=> $_FILES['imageFileName']['name'],
							"categoryId"		   	=> $newid,
							"eventId"				=> null,
							"itemId"				=> null,
							"imageData"				=> $imageFileData,
						];

						$imagerequesturl = "";

						if($imageFileData != "" && !UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
							$apierror = 1;

							header("Location:categories-edit.php?pagenumber=$pagenumber&error=1&id=$newid");
							exit();
						} else {
							if($_POST['saveedit'])
							{
							header("Location:categories-edit.php?pagenumber=$pagenumber&success=1&id=$newid");
							exit();
							}
							elseif($_POST['saveexit'])
							{
							header("Location:categories.php?pagenumber=$pagenumber&success=1");
							exit();
							}
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

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("descriptionclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("descriptionclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("imageFileNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("imageFileNameclass",'textbox-noerror');
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
			$error2 = 'Please enter a description';
			}
			elseif($error2 == "2")
			{
			$error2 = 'Please enter a description of less than 250 characters.';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please upload an image of the correct file type and size.';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>