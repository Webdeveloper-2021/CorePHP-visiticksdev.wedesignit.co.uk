<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	$admintoken = "PERMISSION";
	$adminpermission = "30";

	require($install_path."/includes/visitickets.php");
	require($install_path."/includes/admin.php");

///*****************************************************************************///
///GET VARIABLES///
///*****************************************************************************///

	$pagenumber = $_GET['pagenumber'];
	$id = $_GET['id'];

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}
		else
		{

// DELETE ACTIONS

		$requesturl = "memberships/".$id;
		$requesttype = "DELETE";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);

			$requestresult = json_decode($requestresultarray[1]);

				if($requestresultarray[0] == "SUCCESS")
				{
				header("Location:memberships.php?success=3&pagenumber=$pagenumber");
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
			header("Location:memberships.php?apierror=$apierror&pagenumber=$pagenumber");
			exit();
			}
		}
?>