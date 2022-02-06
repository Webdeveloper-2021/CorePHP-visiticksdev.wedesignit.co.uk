<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SESSION AND UNIVERSAL VARIABLES///
///*****************************************************************************///

	// $uadminloggedin = 1;
	// $uadminpermissions = array('10','20','30','40','50','60','70','80','90','100','110','120','130','140','150','160','170','180');

	if (isset($_SESSION['adminUser']))
	{
		$uadminloggedin = 1;
		$uadminpermissions = $_SESSION['adminUser']['permissions'];
	} else {
		$uadminloggedin = 0;
		$uadminpermissions = array();
	}

		if($admintoken == "ACCOUNT" || $admintoken == "PERMISSION")
		{
			if(!$uadminloggedin)
			{
			header("Location:account-login.php");
			exit();
			}
			else
			{
				if($admintoken == "PERMISSION")
				{
					if(!in_array($adminpermission,$uadminpermissions))
					{
					header("Location:index.php?error=nopermission");
					exit();
					}
				}
			}
		}
		else
		{
			if($admintoken == "LOGGEDOUT")
			{
				if($uadminloggedin)
				{
				header("Location:index.php");
				exit();
				}
			}
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: PRESET CONSTANTS///
///*****************************************************************************///

	define('ADMIN_PERPAGE', "10");
	define('ADMIN_PAGECONTENT','[{"name":"Order Confirmation Page Content","id":"OrderConfirmationText"},{"name":"Order Confirmation (Memberships) Page Content","id":"MembershipOrderConfirmationText"},{"name":"Cookie Policy Page Content","id":"CookiePolicyPageContent"},{"name":"Privacy Page Content","id":"PrivacyPageContent"},{"name":"Refund Policy Page Content","id":"RefundPolicyPageContent"},{"name":"Terms Page Content","id":"TermsPageContent"}]');
	define('ADMIN_EMAILCONTENT','[{"name":"Order Confirmation","id":"OrderConfirmation"},{"name":"Reset Password","id":"ResetPassword"}]');
	define('ADMIN_IMAGEMAXSIZE','2000000');

	define('ADMIN_ERROR_12','The date and time you entered conflicts with one or more existing sessions.');
	define('ADMIN_ERROR_46','This item cannot be deleted because it has an item associated with it.');
	define('ADMIN_ERROR_47','This event cannot be deleted because it is associated with an item.');
	define('ADMIN_ERROR_50','This donation rate cannnot be deleted because it is in use.');
	define('ADMIN_ERROR_51','This customer cannot be deleted because they are associated with orders or subscriptions.');
