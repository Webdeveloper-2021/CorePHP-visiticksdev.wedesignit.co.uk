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

    use includes\classes\controllers\OrderController;

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-account-orders.htm");

	$orders = new OrderController();

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
		    $list = $orders->index();

		    if(empty($list))
		    {
                $PHPTemplateLayer->block("NOORDERS");
            }
		    else
            {
                foreach ($list as $order) {
                    $PHPTemplateLayer->block("ORDER");
                    $PHPTemplateLayer->assign('id', $order->id);
                    $PHPTemplateLayer->assign('reference', $order->reference);
                    $PHPTemplateLayer->assign('createdAt', date('jS F Y', strtotime($order->createdAt)));
                    $PHPTemplateLayer->assign('total', price($order->total));
                }
            }

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);
