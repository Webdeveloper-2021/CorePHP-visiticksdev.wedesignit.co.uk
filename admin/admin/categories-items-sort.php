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
	$itemid = $_GET['itemid'];
	$action = $_GET['action'];

		if(!$id)
		{
		header("Location:index.php?error=unexpected");
		exit();
		}
		else
		{

// SORT ACTIONS

		$requesturl = "catalog/".$id;
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

					$itemKey_1 = array_search($itemid, array_column($objList, 'id'));

					if ($action == "up")
					{
						$itemKey_2 = array_search($objList[$itemKey_1]->displayOrder - 1, array_column($objList, 'displayOrder'));
					}
					else
					{
						$itemKey_2 = array_search($objList[$itemKey_1]->displayOrder + 1, array_column($objList, 'displayOrder'));
					}
// $i = 0;
					foreach($objList as $key => $objItem)
					{
						switch ($objItem->id)
						{
							case $objList[$itemKey_1]->id :
								if (property_exists($objItem, 'item')) {
									$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => $objList[$itemKey_2]->displayOrder);
								}
								if (property_exists($objItem, 'event')) {
									$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => $objList[$itemKey_2]->displayOrder);
								}
								break;
							case $objItem->id == $objList[$itemKey_2]->id :
								if (property_exists($objItem, 'item')) {
									$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => $objList[$itemKey_1]->displayOrder);
								}
								if (property_exists($objItem, 'event')) {
									$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => $objList[$itemKey_1]->displayOrder);
								}
								break;
							default:
								if (property_exists($objItem, 'item')) {
									$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => $objItem->displayOrder);
								}
								if (property_exists($objItem, 'event')) {
									$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => $objItem->displayOrder);
								}
						}
// $i++;
// if (property_exists($objItem, 'item')) {
// 	$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => $i);
// }
// if (property_exists($objItem, 'event')) {
// 	$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => $i);
// }
					}

					$requesturl = "categories/setchilddisplayorder";
					$requesttype = "POST";
					$requestbody = array("categoryId" => $id, "childItems" => $newOrderList);
					
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
			header("Location:categories-items.php?id=$id&pagenumber=$pagenumber");
			exit();
			}
			else
			{
			header("Location:categories-items.php?id=$id&apierror=1&pagenumber=$pagenumber");
			exit();
			}
		}
?>