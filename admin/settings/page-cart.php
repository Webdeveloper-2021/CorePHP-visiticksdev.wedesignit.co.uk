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
    use includes\classes\models\Cart;
    use includes\classes\models\Event;

    $cart = new Cart();
    $basket_items = $cart->items;
	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-cart.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

	if(isset($_SESSION['cart_expired'])) {
        $PHPTemplateLayer->block("EXPIREDNOTICE");
        unset($_SESSION['cart_expired']);
    } else {
        if (!empty($cart->items)) {
            foreach ($cart->items as $item) {
                $PHPTemplateLayer->block("ITEM");

                if ($item->item->itemType == 3) {
                    $PHPTemplateLayer->block("ITEMEVENT");

                    $event = new Event($item->item->eventId);
                    $eventSession = $event->getSession($item->eventSessionId);

                    $eventDate = date('jS F Y', strtotime($eventSession->date));
                    $timeStart = $eventSession->startHour . ":" . str_pad($eventSession->startMinute, 2, '0',
                            STR_PAD_LEFT);

                    if ($eventSession->endHour) {
                        $timeEnd = $eventSession->endHour . ":" . str_pad($eventSession->endMinute, 2, '0',
                                STR_PAD_LEFT);
                        $PHPTemplateLayer->assign("endTime", ' - ' . $timeEnd);
                    }

                    $PHPTemplateLayer->assign("people", $item->quantity);
                    $PHPTemplateLayer->assign("ticketsText", $item->quantity > 1 ? 'Tickets' : 'Ticket');
                    $PHPTemplateLayer->assign("date", $eventDate);
                    $PHPTemplateLayer->assign("startTime", $timeStart);
                    $PHPTemplateLayer->assign("price", price($item->item->unitPrice));
                    $PHPTemplateLayer->assign("eventname", $event->name . ' - ');

                    if ($item->item->id === $event->itemsForMemberships[0]->id) {
                        $PHPTemplateLayer->assign("free", 'free-');
                    }
                } else {
                    $PHPTemplateLayer->block("ITEMPRODUCT");
                }

                $itempicture = SETTING_DEFAULTIMAGE;

                if (isset($item->item->imageFileName)) {
                    $itempicture = API_URL . "images/" . $item->item->imageFileName;
                }

                $PHPTemplateLayer->assign("name", $item->item->name);
                $PHPTemplateLayer->assign("description", get_content_shortversion($item->item->description, 75));
                $PHPTemplateLayer->assign("picture", $itempicture);
                $PHPTemplateLayer->assign("id", $item->id);
                $PHPTemplateLayer->assign("price", price($item->unitPrice));
                // remove used item
                $maxquantity = isset($item->item->purchaseQuantityMaximum) ? $item->item->purchaseQuantityMaximum : 99;

                $basket_items = $cart->items;
                $PHPTemplateLayer->assign("maxquantity", $maxquantity);
                if ($item->item->itemType === 4) {
                    $PHPTemplateLayer->assign("membership", "membership");
                }

                if ($item->item->itemType !== 3) {
                    if (!isset($item->renewalMembershipSubscriptionId) || !$item->renewalMembershipSubscriptionId) {
                        $PHPTemplateLayer->block("VARIABLEQUANTITY");
                    } else {
                        $PHPTemplateLayer->block("STATICQUANTITY");
                    }
                }

                $PHPTemplateLayer->assign("id", $item->id);
                $PHPTemplateLayer->assign("quantity", $item->quantity);
            }
        }

        $PHPTemplateLayer->assignGlobal("items", $cart->itemCount);
        $PHPTemplateLayer->assignGlobal("total", price($cart->total));
        $PHPTemplateLayer->assignGlobal("cartexpires", $cart->expiresAt);

        if ($cart->itemCount > 0) {
            $PHPTemplateLayer->block("CARTWARNING");
            $PHPTemplateLayer->block("CHECKOUT");

            if (SETTING_OFFER_ENABLED) {
                $PHPTemplateLayer->block("OFFER");
                $PHPTemplateLayer->assign("SETTING_OFFER_TEXT", SETTING_OFFER_TEXT);
                $PHPTemplateLayer->assign("SETTING_OFFER_LINK", SETTING_OFFER_LINK);
            }
        } else {
            $PHPTemplateLayer->block("EMPTYNOTICE");
        }
    }

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);