<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "160";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-content-misc.htm");

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

///*****************************************************************************///
///PROCESS CANCEL///
///*****************************************************************************///

		if($_POST['cancel'])
		{
		header("Location:settings-content-misc.php");
		exit();
		}

// Get data for existing category

///*****************************************************************************///
///CATEGORY GROUPS///
///*****************************************************************************///

	$grouprequesturl = "categories";
	$grouprequesttype = "GET";
	$grouprequestbody = "";

	$grouprequestresult = process_connect_Curl($U_SESSION_API_TOKEN,$grouprequesturl,$grouprequesttype,$grouprequestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

		if($grouprequestresult != "ERROR")
		{
		$grouprequestresultarray = explode('|X|',$grouprequestresult);
		$grouprequestresult = json_decode($grouprequestresultarray[1]);

			if($grouprequestresultarray[0] != "FAIL")
			{
			$grouptotalresults = count($grouprequestresult);

				if($grouptotalresults)
				{
				$PHPTemplateLayer->block("CATEGORIES");

					foreach($grouprequestresult as $groupcategorykey => $groupcategoryobject)
					{
					$PHPTemplateLayer->block("CATEGORY");

					$grouptitle = $groupcategoryobject->name;
					$groupid = $groupcategoryobject->id;

						if($_POST['saveedit'] || $_POST['saveexit'])
						{
							if($_POST['CheckoutAddMoreItemsCategoryId'] == $groupid)
							{
							$groupselected = 'selected="selected"';
							}
							else
							{
							$groupselected = '';
							}
						}
						else
						{
							if(SETTING_OFFER_LINK == $groupid)
							{
							$groupselected = 'selected="selected"';
							}
							else
							{
							$groupselected = '';
							}
						}

					$PHPTemplateLayer->assign("categoryid",$groupid);
					$PHPTemplateLayer->assign("categorytitle",$grouptitle);
					$PHPTemplateLayer->assign("categoryselected",$groupselected);
					}
				}
				else
				{
				$PHPTemplateLayer->block("NOCATEGORIES");
				}
			}
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($_POST['saveedit'])
		{
		$GiftAidTitleText = stripslashes($_POST['GiftAidTitleText']);
		$GiftAidText = stripslashes($_POST['GiftAidText']);
		$GiftAidYesText = stripslashes($_POST['GiftAidYesText']);
		$GiftAidNoText = stripslashes($_POST['GiftAidNoText']);
		$CheckoutAddMoreItemsEnabled = stripslashes($_POST['CheckoutAddMoreItemsEnabled']);
		$CheckoutAddMoreItemsText = stripslashes($_POST['CheckoutAddMoreItemsText']);
		$CheckoutAddMoreItemsCategoryId = stripslashes($_POST['CheckoutAddMoreItemsCategoryId']);
		$PageFooterText = stripslashes($_POST['PageFooterText']);
		$MembershipPassName = stripslashes($_POST['MembershipPassName']);
		$MembershipPassNamePlural = stripslashes($_POST['MembershipPassNamePlural']);
		$MarketingText = stripslashes($_POST['MarketingText']);
		$TermsConfirmationRequiredOnCheckout = stripslashes($_POST['TermsConfirmationRequiredOnCheckout']);
		$TermsConfirmationSummary = stripslashes($_POST['TermsConfirmationSummary']);
		}
		else
		{
		$GiftAidTitleText = SETTING_GIFTAID_TITLE;
		$GiftAidText = SETTING_GIFTAID_TEXT;
		$GiftAidYesText = SETTING_GIFTAID_YES;
		$GiftAidNoText = SETTING_GIFTAID_NO;
		$CheckoutAddMoreItemsEnabled = SETTING_OFFER_ENABLED;
		$CheckoutAddMoreItemsText = SETTING_OFFER_TEXT;
		$CheckoutAddMoreItemsCategoryId = SETTING_OFFER_LINK;
		$PageFooterText = SETTING_TEXT;
		$MembershipPassName = SETTING_PASSTITLE_SINGLE;
		$MembershipPassNamePlural = SETTING_PASSTITLE_PLURAL;
		$MarketingText = SETTING_MARKETINGTEXT;
		$TermsConfirmationRequiredOnCheckout = SETTING_TANDCREQUIRED;
		$TermsConfirmationSummary = SETTING_TANDCTEXT;
		}

	$PHPTemplateLayer->assignGlobal("GiftAidTitleText",htmlspecialchars($GiftAidTitleText));
	$PHPTemplateLayer->assignGlobal("GiftAidText",$GiftAidText);
	$PHPTemplateLayer->assignGlobal("GiftAidYesText",htmlspecialchars($GiftAidYesText));
	$PHPTemplateLayer->assignGlobal("GiftAidNoText",htmlspecialchars($GiftAidNoText));
	$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsText",htmlspecialchars($CheckoutAddMoreItemsText));
	$PHPTemplateLayer->assignGlobal("PageFooterText",$PageFooterText);
	$PHPTemplateLayer->assignGlobal("MembershipPassName",htmlspecialchars($MembershipPassName));
	$PHPTemplateLayer->assignGlobal("MembershipPassNamePlural",htmlspecialchars($MembershipPassNamePlural));
	$PHPTemplateLayer->assignGlobal("MarketingText",htmlspecialchars($MarketingText));
	$PHPTemplateLayer->assignGlobal("TermsConfirmationSummary",$TermsConfirmationSummary);

		if($CheckoutAddMoreItemsEnabled == "1")
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsEnabledchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsEnabledchecked",'');
		}

		if($TermsConfirmationRequiredOnCheckout == "1")
		{
		$PHPTemplateLayer->assignGlobal("TermsConfirmationRequiredOnCheckoutchecked",'checked="checked"');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("TermsConfirmationRequiredOnCheckoutchecked",'');
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($GiftAidTitleText == "" || !form_valid_string('VARCHAR',$GiftAidTitleText))
			{
			$error = "1";
			$error1 = "1";
			}

			if($GiftAidText == "" || !form_valid_string('TEXT',$GiftAidText))
			{
			$error = "1";
			$error2 = "1";
			}

			if($GiftAidYesText == "" || !form_valid_string('VARCHAR',$GiftAidYesText))
			{
			$error = "1";
			$error3 = "1";
			}

			if($GiftAidNoText == "" || !form_valid_string('VARCHAR',$GiftAidNoText))
			{
			$error = "1";
			$error4 = "1";
			}

			if($TermsConfirmationRequiredOnCheckout == "1")
			{
				if($TermsConfirmationSummary == "" || !form_valid_string('VARCHAR',$TermsConfirmationSummary))
				{
				$error = "1";
				$error11 = "1";
				}
			}

			if($CheckoutAddMoreItemsEnabled == "1")
			{
				if($CheckoutAddMoreItemsText == "" || !form_valid_string('VARCHAR',$CheckoutAddMoreItemsText))
				{
				$error = "1";
				$error5 = "1";
				}

				if(!CheckoutAddMoreItemsCategoryId || CheckoutAddMoreItemsCategoryId == "")
				{
				$error = "1";
				$error6 = "1";
				}
			}

			if($PageFooterText == "" || !form_valid_string('TEXT',$PageFooterText))
			{
			$error = "1";
			$error7 = "1";
			}

			if($MembershipPassName == "" || !form_valid_string('VARCHAR',$MembershipPassName))
			{
			$error = "1";
			$error8 = "1";
			}

			if($MembershipPassNamePlural == "" || !form_valid_string('VARCHAR',$MembershipPassNamePlural))
			{
			$error = "1";
			$error9 = "1";
			}

			if($MarketingText == "" || !form_valid_string('VARCHAR',$MarketingText))
			{
			$error = "1";
			$error10 = "1";
			}

			if(!$error)
			{
			$GiftAidTitleText = form_process_makesafe($GiftAidTitleText);
			$GiftAidText = form_process_WYSIWYG($GiftAidText);
			$GiftAidYesText = form_process_makesafe($GiftAidYesText);
			$GiftAidNoText = form_process_makesafe($GiftAidNoText);
			$CheckoutAddMoreItemsEnabled = form_process_makesafe($CheckoutAddMoreItemsEnabled);
			$CheckoutAddMoreItemsText = form_process_makesafe($CheckoutAddMoreItemsText);
			$CheckoutAddMoreItemsCategoryId = form_process_makesafe($CheckoutAddMoreItemsCategoryId);
			$PageFooterText = form_process_WYSIWYG($PageFooterText);
			$MembershipPassName = form_process_makesafe($MembershipPassName);
			$MembershipPassNamePlural = form_process_makesafe($MembershipPassNamePlural);
			$MarketingText  = form_process_makesafe($MarketingText);
			$TermsConfirmationRequiredOnCheckout  = form_process_makesafe($TermsConfirmationRequiredOnCheckout);
			$TermsConfirmationSummary  = form_process_WYSIWYG($TermsConfirmationSummary);

			$CheckoutAddMoreItemsEnabled = ReturnZero($CheckoutAddMoreItemsEnabled);
			$TermsConfirmationRequiredOnCheckout = ReturnZero($TermsConfirmationRequiredOnCheckout);

// API ACTION: UPDATE SETTINGS

				if($GiftAidTitleText != SETTING_GIFTAID_TITLE)
				{
					if(!UpdateSettings('GiftAidTitleText',$GiftAidTitleText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($GiftAidText != SETTING_GIFTAID_TEXT)
				{
					if(!UpdateSettings('GiftAidText',$GiftAidText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($GiftAidYesText != SETTING_GIFTAID_YES)
				{
					if(!UpdateSettings('GiftAidYesText',$GiftAidYesText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($GiftAidNoText != SETTING_GIFTAID_NO)
				{
					if(!UpdateSettings('GiftAidNoText',$GiftAidNoText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CheckoutAddMoreItemsEnabled != SETTING_OFFER_ENABLED)
				{
					if(!UpdateSettings('CheckoutAddMoreItemsEnabled',$CheckoutAddMoreItemsEnabled,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CheckoutAddMoreItemsText != SETTING_OFFER_TEXT)
				{
					if(!UpdateSettings('CheckoutAddMoreItemsText',$CheckoutAddMoreItemsText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($CheckoutAddMoreItemsCategoryId != SETTING_OFFER_LINK)
				{
					if(!UpdateSettings('CheckoutAddMoreItemsCategoryId',$CheckoutAddMoreItemsCategoryId,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($PageFooterText != SETTING_TEXT)
				{
					if(!UpdateSettings('PageFooterText',$PageFooterText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($MembershipPassName != SETTING_PASSTITLE_SINGLE)
				{
					if(!UpdateSettings('MembershipPassName',$MembershipPassName,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($MembershipPassNamePlural != SETTING_PASSTITLE_PLURAL)
				{
					if(!UpdateSettings('MembershipPassNamePlural',$MembershipPassNamePlural,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($MarketingText != SETTING_MARKETINGTEXT)
				{
					if(!UpdateSettings('MarketingText',$MarketingText,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($TermsConfirmationRequiredOnCheckout != SETTING_TANDCREQUIRED)
				{
					if(!UpdateSettings('TermsConfirmationRequiredOnCheckout',$TermsConfirmationRequiredOnCheckout,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if($TermsConfirmationSummary != SETTING_TANDCTEXT)
				{
					if(!UpdateSettings('TermsConfirmationSummary',$TermsConfirmationSummary,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
					{
					$apierror = 1;
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

				header("Location:settings-content-misc.php?success=1");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("GiftAidTitleTextTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("GiftAidTitleTextclass",'textbox-noerror');
		}

		if($error2 != "")
		{
		$PHPTemplateLayer->assignGlobal("GiftAidTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("GiftAidTextclass",'textbox-noerror');
		}

		if($error3 != "")
		{
		$PHPTemplateLayer->assignGlobal("GiftAidYesTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("GiftAidYesTextclass",'textbox-noerror');
		}

		if($error4 != "")
		{
		$PHPTemplateLayer->assignGlobal("GiftAidNoTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("GiftAidNoTextclass",'textbox-noerror');
		}

		if($error5 != "")
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsTextclass",'textbox-noerror');
		}

		if($error6 != "")
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsCategoryIdclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("CheckoutAddMoreItemsCategoryIdclass",'textbox-noerror');
		}

		if($error7 != "")
		{
		$PHPTemplateLayer->assignGlobal("PageFooterTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("PageFooterTextclass",'textbox-noerror');
		}

		if($error8 != "")
		{
		$PHPTemplateLayer->assignGlobal("MembershipPassNameclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("MembershipPassNameclass",'textbox-noerror');
		}

		if($error9 != "")
		{
		$PHPTemplateLayer->assignGlobal("MembershipPassNamePluralclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("MembershipPassNamePluralclass",'textbox-noerror');
		}

		if($error10 != "")
		{
		$PHPTemplateLayer->assignGlobal("MarketingTextclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("MarketingTextclass",'textbox-noerror');
		}

		if($error11 != "")
		{
		$PHPTemplateLayer->assignGlobal("TermsConfirmationSummaryclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("TermsConfirmationSummaryclass",'textbox-noerror');
		}

///*****************************************************************************///
///IS USER INPUT ERRORS RETURN APPROPRIATE MESSAGES///
///*****************************************************************************///

$apierror = 0;

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
			$error1 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);

			if($error2 == "1")
			{
			$error2 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error2",$error2);

			if($error3 == "1")
			{
			$error3 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error3",$error3);

			if($error4 == "1")
			{
			$error4 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error4",$error4);

			if($error5 == "1")
			{
			$error5 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error5",$error5);

			if($error6 == "1")
			{
			$error6 = 'Please select a category';
			}

		$PHPTemplateLayer->assignGlobal("error6",$error6);

			if($error7 == "1")
			{
			$error7 = 'Please enter a value';
			}

		$PHPTemplateLayer->assignGlobal("error7",$error7);

			if($error8 == "1")
			{
			$error8 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error8",$error8);

			if($error9 == "1")
			{
			$error9 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error9",$error9);

			if($error10 == "1")
			{
			$error10 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error10",$error10);

			if($error11 == "1")
			{
			$error11 = 'Please enter a value up to 250 characters';
			}

		$PHPTemplateLayer->assignGlobal("error11",$error11);
		}
		elseif($success != "")
		{
		$PHPTemplateLayer->block("SUCCESS");

			if($success == "1")
			{
			$success = 'You have successfully updated your settings';
			}

		$PHPTemplateLayer->assign("success",$success);
		}

	$PHPTemplateLayer->display('','','MINIFY');
?>