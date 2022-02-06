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

    use includes\classes\models\Event;
    use includes\classes\models\Customer;

///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

    $event = $_POST['eventid'];
	$people = (int) $_POST['qty'];

///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

	$error = false;

	$response = array(
	    "completed" => "",
        "error" => "",
        "enough" => false
    );

///*****************************************************************************///
///CHECK DATA///
///*****************************************************************************///

        if (!$event) {
            $error = true;
        }

        if (!$people) {
            $error = true;
        }

        if(!$error)
        {
            $event = new Event($event);
            $customer = new Customer;

            $validForPeopleCount = 0;

            if ($event->hasMembershipEventDiscountedItem) {
                if (!empty($customer->memberships)) {
                    foreach ($customer->memberships as $membership) {
                        if (time() < strtotime($membership->expirationDate) && !$membership->cancelled) {
                            if (in_array($membership->membershipId, $event->validMemberships)) {
                                $validForPeopleCount += $membership->people;
                            }
                        }
                    }
                }

                if ($people) {
                    if ($validForPeopleCount >= $people) {
                        $_SESSION['tempEventWithMemberships'][$event->id] = true;
                        $response["completed"] = true;
                        $response["enough"] = true;
                        $response["people"] = $people;
                        unset($_SESSION['tempEventWithMembershipsNotEnough']);
                    } else {
                        $_SESSION['tempEventWithMemberships'][$event->id] = true;
                        $response["completed"] = true;
                        $response["people"] = $people;

                        $_SESSION['tempEventWithMembershipsNotEnough'] = true;
                    }
                } else {
                    $response["error"] = SETTING_GENERIC_ERROR_MSG;
                }
            }
            else
            {
                $response["error"] = "You can not use memberships for this event.";
            }
        }
        else
        {
            $response["error"] = SETTING_GENERIC_ERROR_MSG;
        }

	echo json_encode($response);