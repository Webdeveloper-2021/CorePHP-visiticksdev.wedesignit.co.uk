<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

use includes\classes\API;

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$response = array("content" => "ERROR");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-categories.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORIES///
///*****************************************************************************///

	$requestresult = API::get('catalog');

		if($requestresult['ok'])
		{
			usort($requestresult['content'], 'process_content_displayOrder');

			$categoryarraycount = 0;

				foreach($requestresult['content'] as $categorykey => $categoryobject)
				{
				$PHPTemplateLayer->block("CATEGORY");

				$categoryarraycount = $categoryarraycount+1;
				$categoryclass = get_content_oddeven($categoryarraycount);

				$categorytitle = isset($categoryobject->name) ? get_content_shortversion($categoryobject->name, 30) : null;
				$categorydescription = isset($categoryobject->description) ? get_content_shortversion($categoryobject->description, 69) : null;
				$categorypicture = isset($categoryobject->imageFileName) ? trailingslashit(API_URL)."images/".$categoryobject->imageFileName : SETTING_DEFAULTIMAGE;

				$categoryid = $categoryobject->id;

				$PHPTemplateLayer->assign("categorytitle",$categorytitle);
				$PHPTemplateLayer->assign("categorydescription",$categorydescription);
				$PHPTemplateLayer->assign("categorypicture",$categorypicture);
				$PHPTemplateLayer->assign("categoryid",$categoryid);
				$PHPTemplateLayer->assign("categoryclass",$categoryclass);
				}

			$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
		}

	echo json_encode($response);