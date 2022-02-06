<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

	header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-home.htm");

	$categoriespicture = SETTING_DEFAULTIMAGE;
	$calendarpicture = SETTING_DEFAULTIMAGE;
		
		if(SETTING_HOME_CATEGORY_IMAGE && SETTING_HOME_CATEGORY_IMAGE != "")
		{
		$categoriespicture = trailingslashit(API_URL) . "images/" . SETTING_HOME_CATEGORY_IMAGE;
		}

		if(SETTING_HOME_EVENT_IMAGE && SETTING_HOME_EVENT_IMAGE != "")
		{
		$calendarpicture = trailingslashit(API_URL) . "images/" . SETTING_HOME_EVENT_IMAGE;
		}

	$PHPTemplateLayer->assignGlobal("SETTING_HOME_CATEGORY_TEXT",get_content_shortversion(SETTING_HOME_CATEGORY_TEXT,140));
	$PHPTemplateLayer->assignGlobal("SETTING_HOME_CATEGORY_IMAGE",$categoriespicture);
	$PHPTemplateLayer->assignGlobal("SETTING_HOME_CATEGORY_BUTTON",SETTING_HOME_CATEGORY_BUTTON);
	$PHPTemplateLayer->assignGlobal("SETTING_HOME_EVENT_TEXT",get_content_shortversion(SETTING_HOME_EVENT_TEXT,140));
	$PHPTemplateLayer->assignGlobal("SETTING_HOME_EVENT_IMAGE",$calendarpicture);
	$PHPTemplateLayer->assignGlobal("SETTING_HOME_EVENT_BUTTON",SETTING_HOME_EVENT_BUTTON);
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);