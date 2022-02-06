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
	$memberships = array_filter($_POST['qty']);
	$people = $_POST['people'];

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

        if (empty($memberships)) {
            $error = true;
        }

        if(!$error)
        {
            $event = new Event($event);
            $membershipsForPeopleSelected = 0;
            $_SESSION['tempEventMemberships'][$event->id] = [];

            if ($event->hasMembershipEventDiscountedItem) {
                foreach ($event->memberships as $membership) {
                    foreach ($membership->items as $item) {
                        if (isset($memberships[$item->id])) {
                            $membershipsForPeopleSelected += $membership->numberOfPeople * (int) $memberships[$item->id];
                            $_SESSION['tempEventMemberships'][$event->id][$item->id] = (int) $memberships[$item->id];
                        }
                    }
                }

                if ($_SESSION['tempEventWithMemberships']) {
                    $customer = new Customer;

                    if (!empty($customer->memberships)) {
                        foreach ($customer->memberships as $membership) {
                            if (time() < strtotime($membership->expirationDate)) {
                                if (in_array($membership->membershipId, $event->validMemberships)) {
                                    $membershipsForPeopleSelected += $membership->people;
                                }
                            }
                        }
                    }
                }

                if ($people) {
                    if ($membershipsForPeopleSelected >= $people) {
                        $response["completed"] = true;
                        $response["people"] = $people;
                    } else {
                        $response["error"] = "Not enough memberships selected for the number of tickets you have requested.";
                    }
                } else {
                    $response["completed"] = true;
                    $response["people"] = $membershipsForPeopleSelected;
                }
            }
            else
            {
                $response["error"] = "You can not use memberships for this event.";
            }
        }
        else
        {
            $response["error"] = "Select at least one membership you wish to buy.";
        }

	echo json_encode($response);