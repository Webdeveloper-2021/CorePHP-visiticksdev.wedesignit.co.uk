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

	use includes\classes\controllers\EventController;

	$eventController = new EventController();

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-calendar.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

    $from = $_POST['contentid'] ?? date('Ymd');

    $day = date('jS F Y');

    if ($from) {
        $from = date('Ymd', strtotime($from));
        $to = date('Ymd', strtotime($from) + 24 * 60 * 60);
        $day = date('jS F Y', strtotime($from));
        $events = $eventController->index($from, $to);

        if (!empty($events)) {
            $PHPTemplateLayer->block('EVENTSFOUND');
            $iteration = 1;

            foreach ($events as $event) {
                $PHPTemplateLayer->block('EVENT');

                $picture = SETTING_DEFAULTIMAGE;

                if (isset($event->imageFileName)) {
                    $picture = trailingslashit(API_URL) . "images/" . $event->imageFileName;
                }

                $PHPTemplateLayer->assign("id", $event->id);
                $PHPTemplateLayer->assign("name", get_content_shortversion($event->name, 30));
                $PHPTemplateLayer->assign("description", get_content_shortversion($event->description, 69));
                $PHPTemplateLayer->assign("picture", $picture);
                $PHPTemplateLayer->assign("minPrice", number_format($event->minPrice,2));
                $PHPTemplateLayer->assign("oddeven", $iteration % 2 == 0 ? 'even' : 'odd');
                $PHPTemplateLayer->assign("url", $event->hasMembershipEventDiscountedItem ? '#event_' . $event->id : '#event-tickets_' . $event->id);
                $iteration++;
            }
        } else {
            $PHPTemplateLayer->assignGlobal("showeventsbtnclass", 'uhide');
            $PHPTemplateLayer->block('NOEVENTS');
        }
    }

    $monthyear = date('F Y', strtotime($from));
    $minDate = date('Ymd');
    $startDate = $minDate >= date('Ymd', strtotime($monthyear)) ? $minDate : date('Ymd', strtotime($monthyear));

    $sessions = $eventController->listSessionsForDateRange($startDate, date('Ymt', strtotime($monthyear)));
    $dates = [];

    foreach ($sessions as $sEvent) {
        $dates[] = date('Ymd', strtotime($sEvent->date));
    }

    $PHPTemplateLayer->assignGlobal("dates", json_encode($dates));
    $PHPTemplateLayer->assignGlobal("from", $from ?: date('Ymd'));
    $PHPTemplateLayer->assignGlobal('day', $day);
    $PHPTemplateLayer->assignGlobal('maxdate', date('Y', strtotime('+5 years')));

    $_SESSION['selected_date'] = $from;

    if (isset($_POST['contentid']))
        $response["scrollTo"] = "#eventsfound";

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);