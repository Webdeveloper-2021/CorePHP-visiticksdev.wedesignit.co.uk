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
	$PHPTemplateLayer->prepare($install_path."/templates/page-login.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$redirect = $_POST['contentid']; // 1 - have membership pass; 2 - buy membership pass; 3 - checkout
$id = $_POST['successid'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

$register_url = '#register';

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

$PHPTemplateLayer->assignGlobal('registerurl', $register_url);

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
		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

echo json_encode($response);