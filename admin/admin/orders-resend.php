<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "90";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

///*****************************************************************************///
///GET VARIABLES///
///*****************************************************************************///

	$pagenumber = $_GET['pagenumber'];
	$search = $_GET['search'];
	$fromDate = $_GET['fromDate'];
	$toDate = $_GET['toDate'];
	$id = $_GET['id'];

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}
		else
		{

// RESEND ACTIONS

		$requestdata = [
		"orderId"             => $id,
		];

		$requesturl = "orders/sendconfirmationemail";
		$requesttype = "POST";
		$requestbody = $requestdata;

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);

			$requestresult = json_decode($requestresultarray[1]);

				if($requestresultarray[0] == "SUCCESS")
				{
				header("Location:orders-edit.php?id=$id&pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search&success=2");
				exit();
				}
				elseif($requestresultarray[0] == "FAIL")
				{
				$apierror = $requestresult[0]->errorNumber;
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

			if($apierror)
			{
			header("Location:orders-edit.php?id=$id&pagenumber=$pagenumber&fromDate=$fromDate&toDate=$toDate&search=$search&apierror=$apierror");
			exit();
			}
		}
?>