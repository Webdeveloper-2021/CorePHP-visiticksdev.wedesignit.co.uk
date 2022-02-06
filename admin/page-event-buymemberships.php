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
	$PHPTemplateLayer->prepare($install_path."/templates/page-event-buymemberships.htm");
    $PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_PLURAL",SETTING_PASSTITLE_PLURAL);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

    $eventid = $_POST['contentid'];
    $people = $_POST['successid'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
            $event = new Event($eventid);
            $PHPTemplateLayer->assignGlobal('id', $event->id);
            $PHPTemplateLayer->assignGlobal('title', $event->name);
            $PHPTemplateLayer->assignGlobal('people', $people);

            if ($event->memberships && !empty($event->memberships))
            {
                foreach($event->memberships as $membership) {
                    foreach($membership->items as $membershipItem) {
                        if ($membershipItem->sellOnline && !$membershipItem->deleted) {
                            $PHPTemplateLayer->block('MEMBERSHIP');
                            $PHPTemplateLayer->assign('id', $membershipItem->id);
                            $PHPTemplateLayer->assign('name', $membershipItem->name);
                            $PHPTemplateLayer->assign('description', $membershipItem->description);
                            $PHPTemplateLayer->assign("price", show_prices_without_donation() ? price($membershipItem->onlinePrice) : price($membershipItem->onlinePrice + $membershipItem->donationRate->amount));
                            $PHPTemplateLayer->assign("donationprice",price($membershipItem->onlinePrice + $membershipItem->donationRate->amount));
                            $PHPTemplateLayer->assign("nodonationprice",price($membershipItem->onlinePrice));
                            $PHPTemplateLayer->assign("minquantity", isset($membershipItem->purchaseQuantityMinimum) ? $membershipItem->purchaseQuantityMinimum : 1);
                            $PHPTemplateLayer->assign("maxquantity", isset($membershipItem->purchaseQuantityMaximum) ? $membershipItem->purchaseQuantityMaximum : 99);

                            if (!$membershipItem->donationItem && isset($membershipItem->donationRate) && isset($membershipItem->donationRate->amount) && $membershipItem->donationRate->amount > 0)
                                $show_donations_toggle = true;
                        }
                    }
                }
            }

            if ($show_donations_toggle) {
                $PHPTemplateLayer->block("DONATIONPRICETOGGLE");
                $PHPTemplateLayer->assign('checked',  show_prices_without_donation() ? '' : 'checked');
            }

            if (isset($_SESSION['tempEventWithMembershipsNotEnough'])) {
                $PHPTemplateLayer->block("NOTENOUGHMEMBERSHIPSMESSAGE");
            } else {
                $PHPTemplateLayer->block("MEMBERSHIPSMESSAGE");
            }

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);