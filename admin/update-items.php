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
		    $cart = new Cart;

		    foreach ($quantities as $item_id => $qty) {
		        if (is_array($qty) && isset($qty['event'])) {
		            foreach ($cart->items as $cartItem) {
		                if ($cartItem->id === $item_id) {
                            $cls = new \stdClass();
                            $cls->eventSessionId = intval($cartItem->eventSessionId);
                            $cls->itemId = intval($cartItem->itemId);
                            $cls->unitPrice = price($cartItem->unitPrice);
                            $cls->unitDonationAmount = price($cartItem->unitDonationAmount);
                            $cls->quantity = intval($qty['event']);

                            $cart->removeItem($item_id);
                            $cart->addItems([$cls]);

                            continue 2;
                        }
                    }
                }

		        if (! $cart->updateItem($item_id, $qty)) {
		            $error = true;
		            break;
                }
            }

		    if ($error) {
                switch ($cart->error) {
                    case 1: // BasketExpired
                        $response["error"] = "Your basket has expired. Start over.";
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
		        $cart->refresh(true);

                $response["completed"] = true;
            }
		}
		else
		{
		$response["error"] = "Please select at least one product you wish to purchase.";
		}

	echo json_encode($response);