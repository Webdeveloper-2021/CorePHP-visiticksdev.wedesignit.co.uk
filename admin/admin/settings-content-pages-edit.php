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
	$PHPTemplateLayer->prepare($install_path."/admin/templates/settings-content-pages-edit.htm");

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
		header("Location:settings-content-pages.php?pagenumber=$pagenumber");
		exit();
		}

///*****************************************************************************///
///MAKE USER INPUT SAFE AND VALIDATE DATA///
///*****************************************************************************///

		if($id == "OrderConfirmationText")
		{
		$pagetitle = 'Order Confirmation Page Content';
		$pagecontentsetting = SETTING_CONFIRMATION;
		}
		elseif($id == "MembershipOrderConfirmationText")
		{
		$pagetitle = 'Order Confirmation (Memberships) Page Content';
		$pagecontentsetting = SETTING_MEMBERSHIP_CONFIRMATION;
		} 
		elseif($id == "TermsPageContent")
		{
		$pagetitle = 'Terms Page Content';
		$pagecontentsetting = SETTING_PAGE_TERMS;
		}
		elseif($id == "PrivacyPageContent")
		{
		$pagetitle = 'Privacy Page Content';
		$pagecontentsetting = SETTING_PAGE_PRIVACY;
		} 
		elseif($id == "RefundPolicyPageContent")
		{
		$pagetitle = 'Refund Policy Page Content';
		$pagecontentsetting = SETTING_PAGE_REFUNDS;
		}
		elseif($id == "CookiePolicyPageContent")
		{
		$pagetitle = 'Cookie Policy Page Content';
		$pagecontentsetting = SETTING_PAGE_COOKIES;
		}

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
		$pagecontent = stripslashes($_POST['pagecontent']);
		}
		else
		{
		$pagecontent = $pagecontentsetting;
		}

	$PHPTemplateLayer->assignGlobal("pagetitle",htmlspecialchars($pagetitle));
	$PHPTemplateLayer->assignGlobal("pagecontent",$pagecontent);

		if($_POST['saveedit'] || $_POST['saveexit'])
		{
			if($pagecontent == "" || !form_valid_string('TEXT',$pagecontent))
			{
			$error = "1";
			$error1 = "1";
			}

			if(!$error)
			{
			$pagecontent = form_process_WYSIWYG($pagecontent);

// API ACTION: UPDATE SETTINGS

				if($id == "OrderConfirmationText")
				{
					if($pagecontent != SETTING_CONFIRMATION)
					{
						if(!UpdateSettings('OrderConfirmationText',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}
				elseif($id == "MembershipOrderConfirmationText")
				{
					if($pagecontent != SETTING_MEMBERSHIP_CONFIRMATION)
					{
						if(!UpdateSettings('MembershipOrderConfirmationText',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				} 
				elseif($id == "TermsPageContent")
				{
					if($pagecontent != SETTING_PAGE_TERMS)
					{
						if(!UpdateSettings('TermsPageContent',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}
				elseif($id == "PrivacyPageContent")
				{
					if($pagecontent != SETTING_PAGE_PRIVACY)
					{
						if(!UpdateSettings('PrivacyPageContent',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				} 
				elseif($id == "RefundPolicyPageContent")
				{
					if($pagecontent != SETTING_PAGE_REFUNDS)
					{
						if(!UpdateSettings('RefundPolicyPageContent',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}
				elseif($id == "CookiePolicyPageContent")
				{
					if($pagecontent != SETTING_PAGE_COOKIES)
					{
						if(!UpdateSettings('CookiePolicyPageContent',$pagecontent,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME))
						{
						$apierror = 1;
						}
					}
				}

				if(!$apierror)
				{
				ReloadSettings();

					if($_POST['saveedit'])
					{
					header("Location:settings-content-pages-edit.php?pagenumber=$pagenumber&success=1&id=$id");
					exit();
					}
					elseif($_POST['saveexit'])
					{
					header("Location:settings-content-pages.php?pagenumber=$pagenumber&success=2");
					exit();
					}
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SET CLASSES///
///*****************************************************************************///

		if($error1 != "")
		{
		$PHPTemplateLayer->assignGlobal("pagecontentclass",'textbox-error');
		}
		else
		{
		$PHPTemplateLayer->assignGlobal("pagecontentclass",'textbox-noerror');
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
			$error1 = 'Please enter content';
			}

		$PHPTemplateLayer->assignGlobal("error1",$error1);
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