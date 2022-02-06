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

	use includes\classes\controllers\OrderController;

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-account-orders-view.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$orderid = $_POST['contentid'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		elseif (!$orderid) {
            $response["redirect"] = "page-account-orders.php";
        }
		else
		{
            $order = new OrderController();
            $order = $order->view($orderid);
            $PHPTemplateLayer->assignGlobal('id', $order->id);
            $PHPTemplateLayer->assignGlobal('reference', $order->reference);
            $PHPTemplateLayer->assignGlobal('createdAt', date('jS F Y', strtotime($order->createdAt)));
            $PHPTemplateLayer->assignGlobal('total', price($order->total));
            $PHPTemplateLayer->assignGlobal('title', $order->billingContact->title);
            $PHPTemplateLayer->assignGlobal('firstName', $order->billingContact->firstName);
            $PHPTemplateLayer->assignGlobal('lastName', $order->billingContact->lastName);
            $PHPTemplateLayer->assignGlobal('phone', $order->billingContact->phone);
            $PHPTemplateLayer->assignGlobal('email', $order->billingContact->email);
            $PHPTemplateLayer->assignGlobal('addressLine1', $order->billingAddress->addressLine1);
            $PHPTemplateLayer->assignGlobal('addressLine2', $order->billingAddress->addressLine2);
            $PHPTemplateLayer->assignGlobal('addressLine3', $order->billingAddress->addressLine3);
            $PHPTemplateLayer->assignGlobal('postcode', $order->billingAddress->postcode);
            $PHPTemplateLayer->assignGlobal('country', $order->billingAddress->country);

            if (!empty($order->orderPayments)) {
                foreach($order->orderPayments as $payment) {
                    if ($payment->amount < 0)
                        continue;

                    $PHPTemplateLayer->block('PAYMENT');

                    switch($payment->paymentMethodId) {
                        case 1:
                            $PHPTemplateLayer->block('CARD');
                            $PHPTemplateLayer->assign('cardnumber', $payment->cardNumber);
                            $PHPTemplateLayer->assign('expirydate', $payment->expiryDate);

                            break;
                        case 2:
                            $PHPTemplateLayer->block('APPLE');
                            break;
                        case 3:
                            $PHPTemplateLayer->block('PAYPAL');
                            break;
                    }

                    $PHPTemplateLayer->assign('amount', $payment->amount);
                }
            }

            if (!empty($order->orderLines)) {
                $events = [];
                $itemsProcessed = [];

                foreach($order->orderLines as $line) {
                    if (in_array($line->item->id, $itemsProcessed) && $line->quantity < 0) {
                        unset($itemsProcessed[$line->item->id]);
                    }

                    $itemsProcessed[$line->item->id] = $line;
                }

                foreach($order->orderLines as $line) {
                    if ($line->item->itemType == 3) {
                        $events[$line->eventSessionId][] = $line;
                        continue;
                    } else {
                        $PHPTemplateLayer->block('ITEM');

                        $PHPTemplateLayer->assign('name', $line->item->name);
                        $PHPTemplateLayer->assign('description', $line->item->description);
                        $PHPTemplateLayer->assign('quantity', $line->quantity);
                        $PHPTemplateLayer->assign('price', price(abs($line->quantity * $line->unitPriceIncludingTax)));
                        if ($line->quantity < 0) {
                            $PHPTemplateLayer->assign('refunded', '(Refunded)');
                            $PHPTemplateLayer->assign('refundedsign', '-');
                        }
                    }
                }

                if (!empty($events)) {
                    foreach ($events as $eventSessionId => $event) {
                        $PHPTemplateLayer->block('EVENT');
                        $PHPTemplateLayer->assign('name', $event[0]->eventSession->event->name);
                        $PHPTemplateLayer->assign('description', get_content_shortversion($event[0]->eventSession->event->description, 120));
                        $PHPTemplateLayer->assign('date', date('jS F Y', strtotime($event[0]->eventSession->date)));
                        $PHPTemplateLayer->assign('starthour', ($event[0]->eventSession->startHour <10 ? '0' : '').$event[0]->eventSession->startHour);
                        $PHPTemplateLayer->assign('startminute',($event[0]->eventSession->startMinute < 10 ? '0' : '' ).$event[0]->eventSession->startMinute);

                        $price = 0;
                        foreach ($event as $line) {
                            $price += $line->unitPriceIncludingTax * $line->quantity;
                        }

                        $PHPTemplateLayer->assign('price', price($price));

                        foreach ($event as $line) {
                            $PHPTemplateLayer->block('EVENTITEM');

                            $PHPTemplateLayer->assign('name', $line->name);
                            $PHPTemplateLayer->assign('quantity', $line->quantity);
                            $PHPTemplateLayer->assign('price', price(abs($line->quantity * $line->unitPriceIncludingTax)));

                            if ($line->quantity < 0) {
                                $PHPTemplateLayer->assign('refunded', '(Refunded)');
                                $PHPTemplateLayer->assign('refundedsign', '-');
                            }
                        }

			if(($event[0]->eventSession->event->changeSessionTimeLimitType === 1 && strtotime($event[0]->eventSession->date) > time()) || ($event[0]->eventSession->event->changeSessionTimeLimitType === 2 && strtotime($event[0]->eventSession->date) < time()))
			{
			$PHPTemplateLayer->block('CHANGEDATETIME');
			$PHPTemplateLayer->assign('eventsessionid', $eventSessionId);
			}

                        if ($event[0]->eventSession->event->changeSessionTimeLimitType === 2) { // afterStart
                            if (strtotime($event[0]->eventSession->date) > time()) {
                                $PHPTemplateLayer->block('CHANGEDATETIME');
                                $PHPTemplateLayer->assign('eventsessionid', $eventSessionId);
                            }
                        }
                    }
                }
            }

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);
