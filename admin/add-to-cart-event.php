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
    use includes\classes\models\Event;
    use includes\classes\models\Item;

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

    $eventid = $_POST['eventid'];
	$tickets = array_filter($_POST['item']);
	$date = $_POST['date'];
	$time = $_POST['time'];
	$eventSessionId = $_POST['eventSessionId'];
	$people = $_POST['people'];

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

        if (!$eventid) {
            $error = true;
        }

        if (!$date) {
            $error = true;
        }

        if (!$time) {
            $error = true;
        }

        if (!$eventSessionId) {
            $error = true;
        }

        if (empty($tickets) && !isset($_SESSION['tempEventWithMemberships'][$eventid])) {
            $error = true;
        }

        if(!$error)
        {
            $cart = new Cart;
            $ev = new Event($eventid);

            $items = [];

            foreach ($tickets as $item_id => $qty) {
                $item = new Item($item_id);

                $cls = new \stdClass();
                $cls->eventSessionId = intval($eventSessionId);
                $cls->itemId = intval($item_id);
                $cls->unitPrice = price($item->unitPrice);
                $cls->unitDonationAmount = price($item->unitDonationAmount);
                $cls->quantity = intval($qty);

                $items[] = $cls;
            }

            if (isset($_SESSION['tempEventWithMemberships'][$eventid])) {
                $validMembershipCount = 0;

                if (!empty($_SESSION['customer']->membershipSubscriptions)) {
                    foreach ($_SESSION['customer']->membershipSubscriptions as $membershipSubscription) {
                        if (strtotime($date) <= strtotime($membershipSubscription->expirationDate)) {
                            if (in_array($membershipSubscription->membershipId, $ev->validMemberships)) {
                                $validMembershipCount += $membershipSubscription->people;
                            }
                        }
                    }
                }

                if ($validMembershipCount >= $people) {
                    $item = new Item($ev->itemsForMemberships[0]->id);

                    $cls = new \stdClass();
                    $cls->eventSessionId = intval($eventSessionId);
                    $cls->itemId = intval($ev->itemsForMemberships[0]->id);
                    $cls->unitPrice = price($item->unitPrice);
                    $cls->unitDonationAmount = price($item->unitDonationAmount);
                    $cls->quantity = intval($people);

                    $items[] = $cls;
                } else {
                    $response["error"] = "One or more of your memberships expire before this date.";
                }
            }

            if (!empty($items)) {
                if (!$cart->addItems($items)) {
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
                    unset($_SESSION['tempEventWithMemberships'][$eventid], $_SESSION['tempEventWithMembershipsNotEnough']);
                    $response["completed"] = true;
                }
            }
        }
        else
        {
            $response["error"] = "Please select at least one product you wish to purchase.";
        }

	echo json_encode($response);