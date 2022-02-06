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

    if ($cart->itemCount < 1)
    {
        echo json_encode(['redirect' => 'page-cart.php']);
        die();
    }

	$response = array();
    $button_text = 'Pay Now';

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-checkout-summary.htm");

	$showGiftAid = false;
	$order_data = $_SESSION['order_data'];

    if (!empty($cart->items))
    {
        foreach ($cart->items as $k => $item)
        {
            $PHPTemplateLayer->block("ITEM");

            if ($item->item->itemType == 3) {
                $PHPTemplateLayer->block("ITEMEVENT");

                $event = new Event($item->item->eventId);
                $eventSession = $event->getSession($item->eventSessionId);

                $eventDate = date('jS F Y', strtotime($eventSession->date));
                $timeStart = $eventSession->startHour . ":" . str_pad($eventSession->startMinute, 2, '0', STR_PAD_LEFT);

                if ($eventSession->endHour) {
                    $timeEnd = $eventSession->endHour . ":" . str_pad($eventSession->endMinute, 2, '0', STR_PAD_LEFT);
                    $PHPTemplateLayer->assign("endTime", ' - ' . $timeEnd);
                }

                $PHPTemplateLayer->assign("people", $item->quantity);
                $PHPTemplateLayer->assign("date", $eventDate);
                $PHPTemplateLayer->assign("startTime", $timeStart);
                $PHPTemplateLayer->assign("price", price($item->item->unitPrice));
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

            if ($item->unitDonationAmount > 0)
                $showGiftAid = true;

            if ($item->item->itemType == 4) {
                $PHPTemplateLayer->block("MEMBERSHIPNAMES");

                $names = [];

                foreach ($order_data['memberships'] as $iteration => $membership) {
                    if ($membership['membershipId'] !== $item->item->membershipId)
                        continue;

                    foreach ($membership['members'] as $member) {
                        $title = SETTING_CAPTURETITLESWITHMEMBERNAMES ? $member['title'] : '';
                        $names[] = $title . ' ' . $member['firstName'] . ' ' . $member['lastName'];
                    }
                }

                $PHPTemplateLayer->assign("names", implode('<br>', $names));
            }
        }
    }

    $PHPTemplateLayer->assignGlobal("items", $cart->itemCount);
    $PHPTemplateLayer->assignGlobal("total", price($cart->total));
    $PHPTemplateLayer->assignGlobal("cartexpires", $cart->expiresAt);

    $PHPTemplateLayer->assignGlobal("addressline1",$order_data['addressLine1']);
    $PHPTemplateLayer->assignGlobal("addressline2",$order_data['addressLine2']);
    $PHPTemplateLayer->assignGlobal("addressline3",$order_data['addressLine3']);
    $PHPTemplateLayer->assignGlobal("postcode",$order_data['postcode']);
    $PHPTemplateLayer->assignGlobal("country",$order_data['country']);
    $PHPTemplateLayer->assignGlobal("title",$order_data['title']);
    $PHPTemplateLayer->assignGlobal("firstname",$order_data['firstName']);
    $PHPTemplateLayer->assignGlobal("lastname",$order_data['lastName']);
    $PHPTemplateLayer->assignGlobal("phone",$order_data['phone']);
    $PHPTemplateLayer->assignGlobal("email",$order_data['email']);

    if ($showGiftAid)
    {
        $PHPTemplateLayer->block("GIFTAID");
        $PHPTemplateLayer->assign("SETTING_GIFTAID_TITLE",SETTING_GIFTAID_TITLE);
        $PHPTemplateLayer->assign("SETTING_GIFTAID_TEXT",SETTING_GIFTAID_TEXT);
        $PHPTemplateLayer->assign("SETTING_GIFTAID_YES",SETTING_GIFTAID_YES);
        $PHPTemplateLayer->assign("SETTING_GIFTAID_NO",SETTING_GIFTAID_NO);
    }

    if ($order_data['orderPayments'][0]['paymentMethodId'] == 1)
    {
        $PHPTemplateLayer->block("PAYMENTDETAILS");
        $PHPTemplateLayer->assignGlobal("cardnumber",$order_data['orderPayments'][0]['cardNumber']);
        $PHPTemplateLayer->assignGlobal("expiry",$order_data['orderPayments'][0]['expiryDate']);
    }

    if (SETTING_TANDCREQUIRED) {
        $PHPTemplateLayer->block("TANDC");
        $PHPTemplateLayer->assign("SETTING_TANDCTEXT",SETTING_TANDCTEXT);
    }

    if ($cart->total == 0) {
        $button_text = 'Book Now';
    }

    $PHPTemplateLayer->assignGlobal("buttontext", $button_text);

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);