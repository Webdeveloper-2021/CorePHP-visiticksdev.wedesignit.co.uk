<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "50";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-donationrates-add.htm");

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
		header("Location:settings-configuration-donationrates.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA | UPDATE DATABASE WITH FAILED LOGIN ATTEMPTS///
///*****************************************************************************///

		if($_POST['type'] && $_POST['type'] == "2")
		{
		$PHPTemplateLayer->assign("typechecked2",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assign("typechecked1",'checked="checked"');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		$type = stripslashes($_POST['type']);
		$amount = stripslashes($_POST['amount']);
		$percentage = stripslashes($_POST['percentage']);
		$roundUpTo = stripslashes($_POST['roundUpTo']);

		$PHPTemplateLayer->assign("name",htmlspecialchars($name));
		$PHPTemplateLayer->assign("amount",htmlspecialchars($amount));
		$PHPTemplateLayer->assign("percentage",htmlspecialchars($percentage));
		$PHPTemplateLayer->assign("roundUpTo",htmlspecialchars($roundUpTo));

			if($name == "" || !form_valid_string('VARCHAR',$name))
			{
			$error = "1";
			$error1 = "1";
			}

			if($type == "1")
			{
				if(!$amount || valid_moneyinput($amount,'0.01','','YES') == "FALSE")
				{
				$error = "1";
				$error2 = "1";
				}
			}
			elseif($type == "2")
			{
				if(!$percentage || valid_moneyinput($percentage,'0.01','100','YES') == "FALSE")
				{
				$error = "1";
				$error3 = "1";
				}
			}

			if($roundUpTo != "")
			{
				if(valid_moneyinput($roundUpTo,'0.01','','YES') == "FALSE")
				{
				$error = "1";
				$error4 = "1";
				}
			}

			if(!$error)
			{
			$name = form_process_makesafe($name);
			$amount = form_process_makesafe($amount);
			$percentage = form_process_makesafe($percentage);
			$roundUpTo = form_process_makesafe($roundUpTo);

				if(!$roundUpTo)
				{
				$roundUpTo = "0";
				}

				if($type == "1")
				{
				$percentage = "";
				}
				elseif($type == "2")
				{
				$amount = "";
				}

// API ACTION: CREATE CATEGORY

			$requestdata = [
			"name"             => $name,
			"type"             => $type,
			"amount"             => $amount,
			"percentage"             => $percentage,
			"roundUpTo"             => $roundUpTo,
			];

			$requesturl = "donationrates";
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
						header("Location:settings-configuration-donationrates-edit.php?pagenumber=$pagenumber&success=1&id=$newid");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:settings-configuration-donationrates.php?pagenumber=$pagenumber&success=1");
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
		$PHPTemplateLayer->assignGlobal("amountclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("amountclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("percentageclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("percentageclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("roundUpToclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("roundUpToclass",'textbox-noerror');
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
			$error2 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter a valid percentage';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'If entering a value, please enter a number';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>