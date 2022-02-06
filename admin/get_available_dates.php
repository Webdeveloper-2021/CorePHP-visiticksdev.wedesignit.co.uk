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
    use includes\classes\controllers\EventController;
    use includes\classes\models\Event;

    $eventController = new EventController();
///*****************************************************************************///
///UNIVERSAL PAGE CODE: GET AND CLEAN VARIABLES///
///*****************************************************************************///

	$year = $_POST['year'];
	$month = $_POST['month'];
	$event = $_POST['event'];

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

        if (empty($year) || empty($month)) {
            $error = true;
        }

		if(!$error)
		{
		    $month = $month < 10 ? '0' . $month : $month;
		    $from = $year . '-' . $month . '-01';
            $monthyear = date('F Y', strtotime($from));
            $minDate = date('Ymd');
            $startDate = $minDate >= date('Ymd', strtotime($monthyear)) ? $minDate : date('Ymd', strtotime($monthyear));

            if ($event) {
                $event = new Event($event);
                $dates = $event->getSessions($startDate, date('Ymt', strtotime($monthyear)));
            } else {
                $sessions = $eventController->listSessionsForDateRange($startDate, date('Ymt', strtotime($monthyear)));
                $dates = [];

                foreach ($sessions as $sEvent) {
                    $dates[] = date('Ymd', strtotime($sEvent->date));
                }
            }

            $response["dates"] = json_encode($dates);
		    $response["completed"] = true;
		}
		else
		{
		$response["error"] = SETTING_GENERIC_ERROR_MSG;
		}

	echo json_encode($response);