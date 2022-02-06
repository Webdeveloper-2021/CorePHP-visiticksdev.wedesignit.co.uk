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

///*****************************************************************************///
///GET VARIABLES///
///*****************************************************************************///

	$pagenumber = $_GET['pagenumber'];
	$id = $_GET['id'];
	$action = $_GET['action'];

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}
		else
		{

// SORT ACTIONS

		$requesturl = "categories";
		$requesttype = "GET";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);
			$requestresult = json_decode($requestresultarray[1]);

				if($requestresultarray[0] == "SUCCESS")
				{
					$newOrderList = array();
					$objList = json_decode($requestresultarray[1]);

					$itemKey_1 = array_search($id, array_column($objList, 'id'));

					if ($action == "up")
					{
						$itemKey_2 = array_search($objList[$itemKey_1]->displayOrder - 1, array_column($objList, 'displayOrder'));
					}
					else
					{
						$itemKey_2 = array_search($objList[$itemKey_1]->displayOrder + 1, array_column($objList, 'displayOrder'));
					}

					foreach($objList as $key => $objItem)
					{
						switch ($objItem->id)
						{
							case $objList[$itemKey_1]->id :
								$newOrderList[] = (object) array('categoryId' => $objItem->id, 'displayOrder' => $objList[$itemKey_2]->displayOrder);
								break;
							case $objItem->id == $objList[$itemKey_2]->id :
								$newOrderList[] = (object) array('categoryId' => $objItem->id, 'displayOrder' => $objList[$itemKey_1]->displayOrder);
								break;
							default:
								$newOrderList[] = (object) array('categoryId' => $objItem->id, 'displayOrder' => $objItem->displayOrder);
						}
					}

					$requesturl = "categories/setdisplayorder";
					$requesttype = "POST";
					$requestbody = $newOrderList;

					$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

					if($requestresult != "ERROR")
					{
					$requestresultarray = explode('|X|',$requestresult);

						if($requestresultarray[0] != "SUCCESS")
						{
							$apierror = 1;
						}
					}
					else
					{
					$apierror = 1;
					}
				}
				else{
					$apierror = 1;
				}
			}
			else
			{
				$apierror = 1;
			}




///*****************************************************************************///
///PAGE MESSAGES///
///*****************************************************************************///
// $apierror = 0;

			if(!$apierror)
			{
			header("Location:categories.php?pagenumber=$pagenumber");
			exit();
			}
			else
			{
			header("Location:categories.php?apierror=1&pagenumber=$pagenumber");
			exit();
			}
		}
?>