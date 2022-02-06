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
    use includes\classes\models\Cart;
    use includes\classes\models\Item;

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

	$quantities = array_filter($_POST['qty']);

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

        if (empty($quantities)) {
            $error = true;
        }

		if(!$error)
		{
		    $cart = new Cart();

		    $items = [];

		    foreach ($quantities as $item_id => $qty) {
		        $item = new Item($item_id);

		        $cls = new \stdClass();
		        $cls->itemId = $item_id;
		        $cls->unitPrice = $item->unitPrice;
		        $cls->unitDonationAmount = $item->unitDonationAmount;
                $cls->quantity = intval($qty);

		        $items[] = $cls;
            }

            if (! $cart->addItems($items)) {
                $error = true;
            }

		    if ($error) {
                switch ($cart->error) {
                    case 1: // BasketExpired
                        $response["error"] = "Your basket has expired. Click 'Add to Cart' again to start a new one.";
                        $cart->expire();
                        break;
                    case 8: // NotEnoughCapacity
                        $response["error"] = "Not enough capacity.";
                        break;
                    case 31: // EventTimeLimitNotAllowed
                        $response["error"] = "Event time limit now allowed.";
                        break;
                    default: // Unknown
                        $response["error"] = SETTING_GENERIC_ERROR_MSG;
                        break;
                }
            } else {
		        $cart->refresh();
                $response["completed"] = true;
            }
		}
		else
		{
		$response["error"] = "Please select at least one product you wish to purchase.";
		}

	echo json_encode($response);