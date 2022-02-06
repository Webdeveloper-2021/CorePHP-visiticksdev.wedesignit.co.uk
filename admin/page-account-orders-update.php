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

    use includes\classes\models\Event;
    use includes\classes\models\Order;

	require("includes/visitickets.php");

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-account-orders-update.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$orderid = (int) $_POST['contentid'];
$eventsessionid = (int) $_POST['successid'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
		    $order = new Order($orderid);

            $PHPTemplateLayer->assignGlobal('id', $order->id);
            $PHPTemplateLayer->assignGlobal('eventsessionid', $eventsessionid);
            $PHPTemplateLayer->assignGlobal('reference', $order->reference);
            $PHPTemplateLayer->assignGlobal('createdAt', date('jS F Y', strtotime($order->createdAt)));

            if (!empty($order->orderLines)) {
                $events = [];

                foreach($order->orderLines as $line) {
                    if ($line->eventSessionId === $eventsessionid) {
                        $events[$line->eventSessionId][] = $line;
                    }
                }

                if (!empty($events)) {
                    $ticketsNeeded = 0;

                    foreach ($events as $eventSessionId => $event) {
                        $PHPTemplateLayer->assignGlobal('date', date('jS F Y', strtotime($event[0]->eventSession->date)));
                        $PHPTemplateLayer->assignGlobal('dayymd', date('Ymd', strtotime($event[0]->eventSession->date)));

                        $timeStart = $event[0]->eventSession->startHour . ":" . str_pad($event[0]->eventSession->startMinute, 2, '0', STR_PAD_LEFT);
                        $PHPTemplateLayer->assignGlobal('timestart', $timeStart);

                        $ev = new Event($event[0]->eventSession->eventId);

                        $from = date('Y-m-d', strtotime($event[0]->eventSession->date));
                        $monthyear = date('F Y', strtotime($from));
                        $minDate = date('Ymd');
                        $startDate = $minDate >= date('Ymd', strtotime($monthyear)) ? $minDate : date('Ymd', strtotime($monthyear));

                        $sessions = $ev->getSessions($startDate, date('Ymt', strtotime($monthyear)));

                        $PHPTemplateLayer->assignGlobal("dates", json_encode($ev->sessions));
                        $PHPTemplateLayer->assignGlobal('eventid', $ev->id);
                        $PHPTemplateLayer->assignGlobal('eventname', $ev->name);

                        foreach ($event as $line) {
                            $PHPTemplateLayer->block('EVENTITEM');

                            $PHPTemplateLayer->assign('name', $line->name);
                            $PHPTemplateLayer->assign('quantity', $line->quantity);
                            $ticketsNeeded += $line->quantity;
                        }

                    }

                    $PHPTemplateLayer->assignGlobal("ticketsNeeded", $ticketsNeeded);
                }
            }

            $PHPTemplateLayer->assignGlobal('maxdate', date('Y', strtotime('+5 years')));
            $response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);
