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
	$PHPTemplateLayer->prepare($install_path."/templates/page-event.htm");
    $PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_SINGLE",SETTING_PASSTITLE_SINGLE);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$eventid = $_POST['contentid'];

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET CATEGORY DETAILS///
///*****************************************************************************///

if($eventid)
{
    $event = new Event($eventid);

    if (!$event->hasMembershipEventDiscountedItem) {
        $response['redirect'] = 'page-event-tickets.php';
        $response['contentid'] = $eventid;
    } else {
        $picture = SETTING_DEFAULTIMAGE;

        if (isset($event->imageFileName)) {
            $picture = trailingslashit(API_URL) . "images/" . $event->imageFileName;
        }

        $PHPTemplateLayer->assignGlobal("id", $event->id);
        $PHPTemplateLayer->assignGlobal("name", $event->name);
        $PHPTemplateLayer->assignGlobal("description", $event->description);
        $PHPTemplateLayer->assignGlobal("picture", $picture);
        $PHPTemplateLayer->assignGlobal("minPrice", number_format($event->minPrice, 2));

        if ($uloggedin) {
            $PHPTemplateLayer->block("MEMBERSHIPBTNSLOGGEDIN");
        } else {
            $PHPTemplateLayer->block("MEMBERSHIPBTNSNOTLOGGEDIN");
        }

        $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');

        unset($_SESSION['tempEventWithMembershipsNotEnough']);
    }
}
else
{
    $response["error"] = "Event not selected.";
}

echo json_encode($response);