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

	use includes\classes\controllers\DonationRateController;

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-configuration-donationrates-edit.htm");

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
		header("Location:settings-configuration-donationrates.php?pagenumber=$pagenumber");
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

// Get data for existing donation rate

	$donationrate = new DonationRateController();
	$donationrate = $donationrate->view($id);

	$name = $donationrate->name;
	$type = $donationrate->type;
	$amount = $donationrate->amount;
	$percentage = $donationrate->percentage;
	$roundUpTo = $donationrate->roundUpTo;

		if($roundUpTo == "0")
		{
		$roundUpTo = "";
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$name = stripslashes($_POST['name']);
		$type = stripslashes($_POST['type']);
		$amount = stripslashes($_POST['amount']);
		$percentage = stripslashes($_POST['percentage']);
		$roundUpTo = stripslashes($_POST['roundUpTo']);
		}

		if($type == "2")
		{
		$PHPTemplateLayer->assign("typechecked2",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assign("typechecked1",'checked="checked"');
		}

	$PHPTemplateLayer->assignGlobal("name",htmlspecialchars($name));
	$PHPTemplateLayer->assignGlobal("amount",htmlspecialchars($amount));
	$PHPTemplateLayer->assignGlobal("percentage",htmlspecialchars($percentage));
	$PHPTemplateLayer->assignGlobal("roundUpTo",htmlspecialchars($roundUpTo));

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
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

// API ACTION: UPDATE DONATION RATE

			$requesturl = "donationrates/".$id;
			$requesttype = "PUT";
			$requestbody = array('name'=>$name,'type'=>$type,'amount'=>$amount,'percentage'=>$percentage,'roundUpTo'=>$roundUpTo);

			$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

				if($requestresult != "ERROR")
				{
				$requestresultarray = explode('|X|',$requestresult);

					if($requestresultarray[0] == "SUCCESS")
					{
						if($_POST['saveedit'])
						{
						header("Location:settings-configuration-donationrates-edit.php?pagenumber=$pagenumber&success=1&id=$id");
						exit();
						}
						elseif($_POST['saveexit'])
						{
						header("Location:settings-configuration-donationrates.php?pagenumber=$pagenumber&success=2");
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