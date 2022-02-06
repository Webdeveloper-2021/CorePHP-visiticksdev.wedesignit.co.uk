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

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-event-tickets.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: TAB VALUES///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CATEGORIES",get_content_shortversion(SETTING_TAB_CATEGORIES,12));
	$PHPTemplateLayer->assignGlobal("SETTING_TAB_CALENDAR",get_content_shortversion(SETTING_TAB_CALENDAR,12));


///*****************************************************************************///
///RESET ERRORS///
///*****************************************************************************///

$error = false;

$response = array(
    "completed" => "",
    "error" => ""
);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GET VARIABLES///
///*****************************************************************************///

$eventid = $_POST['eventid'];
$quantities = array_filter($_POST['qty']);

if (!$eventid) {
    $error = true;
}

if (empty($quantities)) {
    $error = true;
}

if(!$error)
{
    $tickets = [];

    foreach ($quantities as $item_id => $qty) {
        $tickets[$item_id] = $qty;
    }

    $_SESSION['tempEventTickets'][$eventid] = $tickets;

    $response["completed"] = true;
}
else
{
    $response["error"] = SETTING_GENERIC_ERROR_MSG;
}

echo json_encode($response);