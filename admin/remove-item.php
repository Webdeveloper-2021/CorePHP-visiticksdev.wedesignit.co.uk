<?php

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REMOVE ITEM///
///*****************************************************************************///
require("includes/visitickets.php");
use includes\classes\models\Cart;

$cart = new Cart();

$id = $_POST['id'];

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;

$response = array(
    "completed" => "",
    "error" => ""
);

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

if (!$id) {
    $error = true;
}

if(!$error)
{
    $id = form_process_makesafe($id);

    if($cart->removeItem($id))
    {
        $response["completed"] = true;
        $response["cartItemCount"] = $cart->itemCount;
    }
    else
    {
        $response["error"] = SETTING_GENERIC_ERROR_MSG;
    }

}
else
{
    $response["error"] = SETTING_GENERIC_ERROR_MSG;
}

echo json_encode($response);
