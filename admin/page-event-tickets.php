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
	$PHPTemplateLayer->prepare($install_path."/templates/page-event-tickets.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$eventid = $_POST['contentid'] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

if($eventid)
{
    $event = new Event($eventid);

    $PHPTemplateLayer->assignGlobal("id",$event->id);
    $PHPTemplateLayer->assignGlobal("name",$event->name);

    $show_donations_toggle = false;
    $show_description = false;
    $show_total_row = false;

    if (count($event->getSessions())) {
        if (!empty($event->items)) {
            foreach ($event->items as $item) {
                if ($item->membershipEventDiscountedItem || (!isset($item->sellOnline) || !$item->sellOnline)) {
                    continue;
                }

                $PHPTemplateLayer->block("ITEM");

                $itempicture = SETTING_DEFAULTIMAGE;

                if (isset($item->imageFileName)) {
                    $itempicture = trailingslashit(API_URL) . "images/" . $item->imageFileName;
                }

                $PHPTemplateLayer->assign("name", $item->name);
                $PHPTemplateLayer->assign("description", get_content_shortversion($item->description, 75));
                $PHPTemplateLayer->assign("picture", $itempicture);
                $PHPTemplateLayer->assign("id", $item->id);
                $PHPTemplateLayer->assign("price", show_prices_without_donation() || !isset($item->donationRate) ? price($item->onlinePrice) : price($item->onlinePrice + $item->donationRate->amount));
                $PHPTemplateLayer->assign("donationprice", isset($item->donationRate) ? price($item->onlinePrice + $item->donationRate->amount) : price($item->onlinePrice));
                $PHPTemplateLayer->assign("nodonationprice", price($item->onlinePrice));
                $PHPTemplateLayer->assign("minquantity", isset($item->purchaseQuantityMinimum) ? $item->purchaseQuantityMinimum : 1);
                $PHPTemplateLayer->assign("maxquantity", isset($item->purchaseQuantityMaximum) ? $item->purchaseQuantityMaximum : 99);

                if (!$item->donationItem && isset($item->donationRate) && isset($item->donationRate->amount) && $item->donationRate->amount > 0) {
                    $show_donations_toggle = true;
                }

                $show_description = true;
                $show_total_row = true;
            }
        }

        if ($show_donations_toggle) {
            $PHPTemplateLayer->block("DONATIONPRICETOGGLE");
            $PHPTemplateLayer->assign('checked', show_prices_without_donation() ? '' : 'checked');
        }

        //$PHPTemplateLayer->assignGlobal("items", $cart->itemCount);

        if (isset($_SESSION['selected_date'])) {
            $PHPTemplateLayer->block("SELECTEDDATE");
            $PHPTemplateLayer->assign('date', date('jS F Y', strtotime($_SESSION['selected_date'])));
        }

        if ($show_description) {
            $PHPTemplateLayer->block("DESCRIPTION");
        }

        if ($show_total_row) {
            $PHPTemplateLayer->block("TOTAL");
        }
    } else {
        $PHPTemplateLayer->block("NOEVENTSESSIONS");
    }

    $response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
}
else
{
    $response["error"] = "Event not selected.";
}

echo json_encode($response);