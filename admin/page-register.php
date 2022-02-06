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
	$PHPTemplateLayer->prepare($install_path."/templates/page-register.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$redirect = $_POST['contentid'] ?? null; // 1 - have membership pass; 2 - buy membership pass; 3 - checkout
$id = $_POST['successid'] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

if ($redirect)
{
    switch($redirect)
    {
        case 1:
            $redirect = "event-memberships_" . $id;
            $register_url = '#register_1|' . $id;
            break;
        case 2:
            $redirect = "event-buymemberships_" . $id;
            $register_url = '#register_2|' . $id;
            break;
        case 3:
            $redirect = "checkout";
            $register_url = '#register_3|' . $id;
            break;
    }

    $PHPTemplateLayer->block('REDIRECT');
    $PHPTemplateLayer->assign('redirect', $redirect);
}

		if($uloggedin)
		{
		    if ($redirect)
            {
                $response["redirect"] = $redirect;
            }
            else
            {
                $response["redirect"] = "page-account.php";
            }
        }
		else
		{
		$PHPTemplateLayer->assignGlobal("INSTALL_POSTCODELOOKUPAPIKEY",INSTALL_POSTCODELOOKUPAPIKEY);
		$PHPTemplateLayer->assignGlobal("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);

            if (function_exists('countryList') && !empty(countryList())) {
                foreach (countryList() as $key => $name) {
                    $PHPTemplateLayer->block("COUNTRY");
                    $PHPTemplateLayer->assign("countrykey", $key);
                    $PHPTemplateLayer->assign("countryname", $name);
                }
            }

			if(SETTING_CAPTURETITLESWITHNAMES)
			{
			$PHPTemplateLayer->block("SHOWTITLE");
			}

			if(SETTING_MARKETINGALLOWED)
			{
			$PHPTemplateLayer->block("SHOWMARKETING");
			}

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);