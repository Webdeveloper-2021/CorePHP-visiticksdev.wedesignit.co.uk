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
	$PHPTemplateLayer->prepare($install_path."/templates/page-cookies.htm");

	$PHPTemplateLayer->assignGlobal("content",SETTING_PAGE_COOKIES);

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);