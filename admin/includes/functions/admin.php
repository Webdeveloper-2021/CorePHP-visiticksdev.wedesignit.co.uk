<?php

use includes\classes\Cache;

	function ItemViewText($total,$pagenumber,$perpage)
	{
		if($pagenumber && $pagenumber > "0")
		{
		$startrow = $perpage * ($pagenumber);
		}
		else
		{
		$startrow = "0";
		}

		if($total)
		{
		$itemsstart = $startrow+1;

			if($total < $perpage)
			{
			$itemsend = $startrow+$total;
			}
			else
			{
			$itemsend = $startrow+$perpage;
			}

			if($itemsstart == $itemsend)
			{
			$itemsview = 'Showing item <strong>'.$itemsstart.'</strong>';
			}
			else
			{
			$itemsview = 'Showing items <strong>'.$itemsstart.'</strong> to <strong>'.$itemsend.'</strong>';
			}
		}
		else
		{
		$itemsview = '';
		}

	return $itemsview;
	}

	function GetPaging($totalresults,$nextresults,$pagenumber,$baseurl,$perpage)
	{
	$Pagination = "";

		if($pagenumber != "0" || $nextresults)
		{
		$Pagination = '<div class="paging">';
		$Pagination .= '	<ul>';

			if($pagenumber > "0")
			{
			$Pagination .= '		<li><a href="'.$baseurl.''.($pagenumber).'" title="This Page" title="Previous Page">&laquo; Previous</a></li>';
			}

		$Pagination .= '		<li><a href="'.$baseurl.''.($pagenumber+1).'" title="This Page">'.($pagenumber+1).'</a></li>';

			if($nextresults > "0")
			{
			$Pagination .= '		<li><a href="'.$baseurl.''.($pagenumber+2).'" title="Next Page">Next &raquo;</a></li>';
			}
	
		$Pagination .= '	</ul>';
		$Pagination .= '</div>';
		}

	return $Pagination;
	}

	function process_uploadfilemimetype($mimetype,$type)
	{
		if($type == "IMAGE")
		{
			if($mimetype != 'image/pjpeg' && $mimetype != 'image/jpeg' && $mimetype != 'image/jpg' && $mimetype != 'image/gif' && $mimetype != 'image/png')
			{
			$validtoken = "FALSE";
			}
			else
			{
			$validtoken = "TRUE";
			}
		}
		elseif($type == "SVG")
		{
			if($mimetype != 'image/svg+xml')
			{
			$validtoken = "FALSE";
			}
			else
			{
			$validtoken = "TRUE";
			}
		}
		elseif($type == "DOCUMENT")
		{
			if($mimetype != 'application/pdf' && $mimetype != 'application/msword')
			{
			$validtoken = "FALSE";
			}
			else
			{
			$validtoken = "TRUE";
			}
		}
		elseif($type == "TEXT")
		{
			if($mimetype != 'text/plain')
			{
			$validtoken = "FALSE";
			}
			else
			{
			$validtoken = "TRUE";
			}
		}

	return $validtoken;
	}

///*****************************************************************************///

	function process_uploadfilename($string,$random)
	{
	$string = str_replace(array(' ','-','&','~','@','%','!',"'",'Á','(',')','í','á','ó','Ó','Í','ú','É','é'), array('_','_','_','_','_','_','_','_','A','_','_','i','a','o','O','I','u','E','e'), $string);
	$string = stripslashes($string);

		if($random == "TRUE")
		{
		$string = RAND()."-".$string;
		}

	return $string;
	}

///*****************************************************************************///

	function DefaultImage($image,$defaultimage)
	{
		if($image !="")
		{
		$imagefile = API_URL."images/".$image;
		}
		else
		{
		$imagefile = $defaultimage;
		}

	return $imagefile;
	}

///*****************************************************************************///

	function DateTimePicker($input,$string,$output)
	{
		if($input == "JS")
		{
		$datearray = explode('/',$string);
		$year = $datearray[2];
		$month = $datearray[1];
		$day = $datearray[0]; 
		}
		elseif($input == "PHP")
		{
		$datearray = explode('-',$string);
		$year = $datearray[0];
		$month = $datearray[1];
		$day = $datearray[2];
		}

		if($output == "JS")
		{
		$return = $day."/".$month."/".$year;
		}
		elseif($output == "PHP")
		{
		$return = $year."-".$month."-".$day;
		}

	return $return;
	}

	function form_valid_dateinput($string)
	{
	$string = trim($string);

	$datearray = explode('/',$string);

		if(count($datearray) == "3")
		{
			if(checkdate($datearray[1],$datearray[0],$datearray[2]) == "TRUE")
			{
			$valid = "TRUE";
			}
			else
			{
			$valid = "FALSE";
			}
		}
		else
		{
		$valid = "FALSE";
		}

	return $valid;
	}

	function valid_Integer($string,$type,$minlength,$maxlength)
	{
	$integertest = $string/1;

		if(!is_numeric($string) || !is_int($integertest))
		{
		return "FALSE";
		}
		else
		{
			if($type == "VALUE")
			{
				if($string > $maxlength || $string < $minlength)
				{
				return "FALSE";
				}
				else
				{
				return "TRUE";
				}
			}
			elseif($type == "LENGTH")
			{
				if(strlen($string) > $maxlength || strlen($string) < $minlength)
				{
				return "FALSE";
				}
				else
				{
				return "TRUE";
				}
			}
			else
			{
			return "TRUE";
			}
		}
	}

	function valid_moneyinput($string,$min,$max,$decimal)
	{
	$string = trim($string);
	$string = str_replace(',','',$string);

		if($min == "")
		{
		$min = "1";
		}

		if($max == "")
		{
		$max = "100000000";
		}

		if($decimal == "YES")
		{
		$stringarray = explode('.',$string);

			if(count($stringarray) > "2")
			{
			$valid = "FALSE";
			}
			elseif(count($stringarray) == "2")
			{
				if(!form_valid_Integer($stringarray[1],'LENGTH','2','2'))
				{
				$valid = "FALSE";
				}
				else
				{
					if(form_valid_Integer($stringarray[0],'VALUE','0',$max))
					{
					$valid = "TRUE";
					}
					else
					{
					$valid = "FALSE";
					}
				}
			}
			else
			{
				if(form_valid_Integer($string,'VALUE',$min,$max))
				{
				$valid = "TRUE";
				}
				else
				{
				$valid = "FALSE";
				}
			}
		}
		else
		{
			if(form_valid_Integer($string,'VALUE',$min,$max))
			{
			$valid = "TRUE";
			}
			else
			{
			$valid = "FALSE";
			}
		}

	return $valid;
	}

	function ReturnZero($var)
	{
		if($var == "1")
		{
		return true;
		}
		else
		{
		return false;
		}
	}

	function is_hex($hex_code)
	{
		if(preg_match('/^#[a-f0-9]{6}$/i', $hex_code))
		{
		return true;
		}
		else
		{
		return false;
		}
	}

	function ProcessEditImage($imagetype,$originalimage,$newimage)
	{
		if($originalimage != "")
		{
			if($imagetype == "EXISTING")
			{
			$saveimage = $originalimage;
			}
			elseif($imagetype == "NEW")
			{
			$saveimage = $newimage;
			}
			elseif($imagetype == "NONE")
			{
			$saveimage = "";

// IF IMAGE EXISTS, DELETE

			}
		}
		else
		{
		$saveimage = $newimage;
		}

	return $saveimage;
	}

	function ShowAPIError($error)
	{
		if($error == "1")
		{
		$errormessage = SETTING_GENERIC_ERROR_MSG;
		}
		elseif($error == "46")
		{
		$errormessage = ADMIN_ERROR_46;
		}
		elseif($error == "47")
		{
		$errormessage = ADMIN_ERROR_47;
		}
		elseif($error == "50")
		{
		$errormessage = ADMIN_ERROR_50;
		}

	return $errormessage;
	}

	function ReloadSettings()
	{
		$globalSettings = Cache::set('globalSettings', '');
	}

	function UpdateSettings($endpoint,$updatevalue,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME)
	{
	$requesturl = "settings/".$endpoint;
	$requesttype = "PUT";
	$requestbody = array('newValue'=>$updatevalue);

	$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

	$result = true;

		if($requestresult != "ERROR")
		{
		$requestresultarray = explode('|X|',$requestresult);

			if($requestresultarray[0] != "SUCCESS")
			{
			$result = false;
			}
		}
		else
		{
		$result = false;
		}

	return $result;
	}

	function ItemType($type,$variable)
	{
		if($type == "tocode")
		{
			if($variable == "tickets")
			{
			$return = "2";
			}
			elseif($variable == "memberships")
			{
			$return = "4";
			}
			elseif($variable == "events")
			{
			$return = "3";
			}
			elseif($variable == "products")
			{
			$return = "1";
			}
			else
			{
			$return = "2";
			}
		}
		elseif($type == "fancytext")
		{
			if($variable == "2")
			{
			$return = "Ticket";
			}
			elseif($variable == "4")
			{
			$return = "Membership";
			}
			elseif($variable == "3")
			{
			$return = "Event";
			}
			elseif($variable == "1")
			{
			$return = "Product";
			}
			else
			{
			$return = "Ticket";
			}
		}
		else
		{
			if($variable == "2")
			{
			$return = "tickets";
			}
			elseif($variable == "4")
			{
			$return = "memberships";
			}
			elseif($variable == "3")
			{
			$return = "events";
			}
			elseif($variable == "1")
			{
			$return = "products";
			}
			else
			{
			$return = "tickets";
			}
		}

	return $return;
	}

	function AvailableDate($date)
	{
	$date = str_replace('-','',$date);

	return $date;
	}


	function UploadImage($imageData,$imageRequestUrl,$U_SESSION_API_TOKEN,$U_DATE,$U_DATETIME)
	{
	$imageRequestUrl == "" ? $requesturl = "images" : $requesturl = "images/".$imageRequestUrl;
	$requesttype = "POST";
	$requestbody = $imageData;

	$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

	$result = true;

		if($requestresult != "ERROR")
		{
		$requestresultarray = explode('|X|',$requestresult);

			if($requestresultarray[0] != "SUCCESS")
			{
			$result = false;
			}
		}
		else
		{
		$result = false;
		}

	return $result;
	}

	function CategoriesSetDisplayOrder($U_SESSION_API_TOKEN, $U_DATE, $U_DATETIME)
	{
		$requesturl = "categories";
		$requesttype = "GET";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			$result = true;

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);
			$requestresult = json_decode($requestresultarray[1]);

				if($requestresultarray[0] == "SUCCESS")
				{
					$newOrderList = array();
					$objList = json_decode($requestresultarray[1]);

					foreach($objList as $key => $objItem)
					{
						if ($objItem->displayOrder == 0)
						{
							$newOrderList[] = (object) array('categoryId' => $objItem->id, 'displayOrder' => count($objList));
						}
						else
						{
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
							$result = true;
						}
					}
					else
					{
						$result = false;
					}
				}
				else
				{
					$result = false;
				}
			}
			else
			{
				$result = false;
			}

			return $result;
	}


	// Update Categories Items Order
	function CategoriesItemsSetDisplayOrder($id, $U_SESSION_API_TOKEN, $U_DATE, $U_DATETIME)
	{
		$requesturl = "catalog/".$id;
		$requesttype = "GET";
		$requestbody = "";

		$requestresult = process_connect_Curl($U_SESSION_API_TOKEN,$requesturl,$requesttype,$requestbody,API_URL,API_USERNAME,API_PASSWORD,API_USERAGENT,SETTING_LOGS,$U_DATE,$U_DATETIME,INSTALL_TYPE,INSTALL_VERSION);

			$result = true;

			if($requestresult != "ERROR")
			{
			$requestresultarray = explode('|X|',$requestresult);
			$requestresult = json_decode($requestresultarray[1]);

				if($requestresultarray[0] == "SUCCESS")
				{
					$newOrderList = array();
					$objList = json_decode($requestresultarray[1]);

					foreach($objList as $key => $objItem)
					{
						if ($objItem->displayOrder == 0)
						{
							if (property_exists($objItem, 'item')) {
								$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => count($objList));
							}
							if (property_exists($objItem, 'event')) {
								$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => count($objList));
							}
						}
						else
						{
							if (property_exists($objItem, 'item')) {
								$newOrderList[] = (object) array('itemId' => $objItem->id, 'displayOrder' => $objItem->displayOrder);
							}
							if (property_exists($objItem, 'event')) {
								$newOrderList[] = (object) array('eventId' => $objItem->id, 'displayOrder' => $objItem->displayOrder);
							}
						}
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
							$result = true;
						}
					}
					else
					{
						$result = false;
					}
				}
				else
				{
					$result = false;
				}
			}
			else
			{
				$result = false;
			}

			return $result;
	}