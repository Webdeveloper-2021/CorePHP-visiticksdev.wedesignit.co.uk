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
	$PHPTemplateLayer->prepare($install_path."/templates/page-event-memberships.htm");
	$PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_SINGLE",SETTING_PASSTITLE_SINGLE);
	$PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_PLURAL",SETTING_PASSTITLE_PLURAL);

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
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
		    if(!$eventid)
            {
                $response["redirect"] = "page-home.php";
            }
            else
            {
                $event = new Event($eventid);

                if (!$event->hasMembershipEventDiscountedItem) {
                    $response["redirect"] = "page-home.php";
                }
                else
                {
                    $PHPTemplateLayer->assignGlobal("id", $event->id);
                    $PHPTemplateLayer->assignGlobal("name", $event->name);

                    $validMembershipCount = 0;
                    $expiredMembershipCount = 0;

                    if (!empty($_SESSION['customer']->membershipSubscriptions)) {
                        foreach ($_SESSION['customer']->membershipSubscriptions as $membershipSubscription) {
                            if (time() < strtotime($membershipSubscription->expirationDate) && ! $membershipSubscription->cancelled) {
                                if (in_array($membershipSubscription->membershipId, $event->validMemberships)) {
                                    $validMembershipCount++;
                                }
                            } else {
                                $expiredMembershipCount++;
                            }
                        }
                    }

                    $PHPTemplateLayer->assignGlobal("validmembershipcount", $validMembershipCount);

                    if ($expiredMembershipCount) {
                        $PHPTemplateLayer->block('EXPIREDMEMBERSHIPS');
                        $PHPTemplateLayer->assign("expiredmembershipcount", $expiredMembershipCount);
                    }

                    $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
                }
            }
        }

    echo json_encode($response);