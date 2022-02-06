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

	use includes\classes\controllers\CategoryController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/categories-edit.htm");

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

	$PHPTemplateLayer->assignGlobal("contentid",$id);
	$PHPTemplateLayer->assignGlobal("pagenumber",$pagenumber);

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:categories.php?pagenumber=$pagenumber");
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

	$category = new CategoryController();
	$category = $category->view($id);

	$name = $category->name;
	$description = $category->description;
	$status = $category->visible;
	$image = $category->imageFileName;

	$categorypicture = DefaultImage($image,SETTING_DEFAULTIMAGE);
	$PHPTemplateLayer->assignGlobal("contenttitle",htmlspecialchars($name));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		$description = stripslashes($_POST['description']);
		$status = stripslashes($_POST['status']);
		$imagetype = stripslashes($_POST['imagetype']);

			if($status == "HIDDEN")
			{
			$PHPTemplateLayer->assignGlobal("statuschecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("statuschecked1",'checked="checked"');
			}

			if($imagetype == "EXISTING")
			{
			$PHPTemplateLayer->assignGlobal("imagetypechecked1",'checked="checked"');
			}
			elseif($imagetype == "NEW")
			{
			$PHPTemplateLayer->assignGlobal("imagetypechecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("imagetypechecked3",'checked="checked"');
			}
		}
		else
		{
			if(!$status)
			{
			$PHPTemplateLayer->assignGlobal("statuschecked2",'checked="checked"');
			}
			else
			{
			$PHPTemplateLayer->assignGlobal("statuschecked1",'checked="checked"');
			}
		}

		if($image != "")
		{
		$PHPTemplateLayer->block("IMG");
		$PHPTemplateLayer->assign("categorypicture",$categorypicture);

		$PHPTemplateLayer->assign("imagetypechecked1",'checked="checked"');
		}

	$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($name));
	$PHPTemplateLayer->assignGlobal("description",$description);

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
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
			else
			{
				if($imagetype == "NEW")
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
				$visible = true;
				}
				else
				{
				$visible = false;
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

			$saveimageFileName = ProcessEditImage($imagetype,$image,$imageFileName);

			$imageFileData = $_POST['imageFileData'];

			if (($imagetype == "NEW" || $imagetype == "") && $imageFileData !== "") {

				$imagerequestbody = [
					"originalFileName"     	=> $saveimageFileName,
					"categoryId"		   	=> $id,
					"eventId"				=> null,
					"itemId"				=> null,
					"imageData"				=> $imageFileData,
				];

				$imagerequesturl = "";
				
				if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
				{
					$apierror = 1;

					header("Location:categories-edit.php?pagenumber=$pagenumber&error=1&id=$id");
					exit();
				}
			}

			if ($imagetype == "NONE") {
				$imagerequestbody = [
					"categoryId"		   	=> $id,
					"eventId"				=> null,
					"itemId"				=> null,
				];

				$imagerequesturl = "clearimage";
				
				if(!UploadImage($imagerequestbody,$imagerequesturl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
				{
					$apierror = 1;

					header("Location:categories-edit.php?pagenumber=$pagenumber&error=1&id=$id");
					exit();
				}
			}

// API ACTION: UPDATE CATEGORY

			$requesturl = "categories/".$id;
			$requesttype = "PUT";
			$requestbody = array('name'=>$name,'description'=>$description,'visible'=>$visible,'imageFileName'=>$saveimageFileName);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:categories-edit.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:categories.php?pagenumber=$pagenumber&success=2");
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