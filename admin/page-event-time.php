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

    use includes\classes\models\Event;

    $response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-event-time.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$eventid = $_POST['contentid'] ?? null;
$people = $_POST['successid'] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

if($eventid)
{
    if(!isset($_SESSION['tempEventTickets'][$eventid]) && !isset($_SESSION['tempEventMemberships'][$eventid]) && !isset($_SESSION['tempEventWithMemberships'][$eventid]))
    {
        $response["redirect"] = "page-event.php";
        $response["contentid"] = $eventid;
    }
    elseif (isset($_SESSION['tempEventMemberships'][$eventid]) && !$people)
    {
        $response["redirect"] = "page-event.php";
        $response["contentid"] = $eventid;
    }
    else
    {
        $event = new Event($eventid);

        $from = isset($_SESSION['selected_date']) ? date('Y-m-d', strtotime($_SESSION['selected_date'])) : date('Y-m-d');
        $monthyear = date('F Y', strtotime($from));
        $minDate = date('Ymd');
        $startDate = $minDate >= date('Ymd', strtotime($monthyear)) ? $minDate : date('Ymd', strtotime($monthyear));
        $sessions = $event->getSessions($startDate, date('Ymt', strtotime($monthyear)));

        $PHPTemplateLayer->assignGlobal('day', isset($_SESSION['selected_date']) ? date('jS F Y', strtotime($_SESSION['selected_date'])) : date('jS F Y'));
        $PHPTemplateLayer->assignGlobal('dayymd', isset($_SESSION['selected_date']) ? date('Ymd', strtotime($_SESSION['selected_date'])) : date('Ymd'));
        $PHPTemplateLayer->assignGlobal('dayymddashed', isset($_SESSION['selected_date']) ? date('Y-m-d', strtotime($_SESSION['selected_date'])) : date('Y-m-d'));
        $PHPTemplateLayer->assignGlobal("id", $event->id);
        $PHPTemplateLayer->assignGlobal("name", $event->name);
        $PHPTemplateLayer->assignGlobal("dates", json_encode($event->sessions));

        $ticketsNeeded = 0;

        foreach ($_SESSION['tempEventTickets'][$eventid] as $itemid => $qty) {
            $PHPTemplateLayer->block("TICKET");
            $PHPTemplateLayer->assign('itemid', $itemid);
            $PHPTemplateLayer->assign('qty', $qty);
            $ticketsNeeded += $qty;
        }

        if (isset($_SESSION['tempEventMemberships'][$eventid]) && !empty($_SESSION['tempEventMemberships'][$eventid])) {
            $PHPTemplateLayer->block("TICKET");
            $PHPTemplateLayer->assign('itemid', $event->itemsForMemberships[0]->id);
            $PHPTemplateLayer->assign('qty', $people);
            $ticketsNeeded += $people;

            foreach ($_SESSION['tempEventMemberships'][$eventid] as $itemid => $qty) {
                $PHPTemplateLayer->block("MEMBERSHIP");
                $PHPTemplateLayer->assign('itemid', $itemid);
                $PHPTemplateLayer->assign('qty', $qty);
                $ticketsNeeded += $qty;
            }
        }

        $PHPTemplateLayer->assignGlobal("ticketsNeeded", $ticketsNeeded);
        $PHPTemplateLayer->assignGlobal("people", $people);
        $PHPTemplateLayer->assignGlobal('maxdate', date('Y', strtotime('+5 years')));

        $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
    }
}
else
{
    $response["error"] = "Event not selected.";
}

echo json_encode($response);