<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES///
///*****************************************************************************///

require("includes/visitickets.php");
use includes\classes\models\Order;

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

$eventSessionId = (int) $_POST['eventSessionId'];
$originalEventSessionId = (int) $_POST['originalEventSessionId'];
$orderId = (int) $_POST['orderId'];

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

if (!$eventSessionId) {
    $error = true;
}

if (!$originalEventSessionId) {
    $error = true;
}

if (!$orderId) {
    $error = true;
}

if(!$error)
{
    $order = new Order($orderId);

    if ($order->customerId !== $_SESSION['customer_id']) {
        $response["error"] = "Order does not belong to you.";
    } else {
        if (!empty($order->orderLines)) {
            foreach ($order->orderLines as $orderLine) {
                if ($orderLine->eventSessionId === $originalEventSessionId) {
                    $order->changeLineEventSession($orderLine->id, $eventSessionId);
                }
            }
        }

        if ($order->error) {
            $response["error"] = $order->error;
        } else {
            $order->sendConfirmationEmail();
            $response["completed"] = true;
        }
    }
}
else
{
    $response["error"] = SETTING_GENERIC_ERROR_MSG;
}

echo json_encode($response);