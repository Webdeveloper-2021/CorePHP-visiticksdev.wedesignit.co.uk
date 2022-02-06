<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///ALLOW ALL ACCESS///
///*****************************************************************************///

use includes\classes\models\Customer;

header('Content-Type: application/json');

///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-account-memberships.htm");
    $PHPTemplateLayer->assignGlobal("SETTING_PASSTITLE_PLURAL",SETTING_PASSTITLE_PLURAL);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
		    $customer = new Customer;
		    $customer->getMemberships();
		    $membershipCount = 0;

            if(!empty($customer->memberships)) {
                foreach ($customer->memberships as $membership) {
                    if ($membership->cancelled)
                        continue;
                    //dd($membership);
                    $membershipCount++;
                    $PHPTemplateLayer->block("MEMBERSHIP");
                    $PHPTemplateLayer->assign('id', $membership->membershipId);
                    $PHPTemplateLayer->assign('name', $membership->membership->name);
                    $PHPTemplateLayer->assign('reference', $membership->reference);
                    $PHPTemplateLayer->assign('expiresat', date('jS F Y', strtotime($membership->expirationDate)));

                    $expires_soon = (strtotime($membership->expirationDate) - time()) / 60 / 60 / 24 <= SETTING_MIN_MEMBERSHIP_RENEWAL_DAYS;

                    $PHPTemplateLayer->assign('expirationclass', $expires_soon ? 'uinlineerror' : '');

                    if ($expires_soon && $membership->membership->membershipLengthType != 3 && $membership->membership->membershipLengthType != 4) {
                        $PHPTemplateLayer->block("RENEW");
                        $PHPTemplateLayer->assign('id', $membership->membership->items[0]->id);
                        $PHPTemplateLayer->assign('membershipid', $membership->id);
                    }

                    if (!empty($membership->members)) {
                        foreach ($membership->members as $member) {
                            $PHPTemplateLayer->block("MEMBER");
                            $PHPTemplateLayer->assign('title', $membership->contact->title);

                            if ($member->contact->firstName || $member->contact->lastName) {
                                $PHPTemplateLayer->block("NAME");
                                $PHPTemplateLayer->assign('name', $member->contact->firstName . ' ' . $member->contact->lastName);
                                $PHPTemplateLayer->assign('child', $member->child ? 'Child ' : '');
                            }

                            if ($member->contact->dateOfBirth) {
                                $PHPTemplateLayer->block("DOB");
                                $PHPTemplateLayer->assign('dob', date('jS F Y', strtotime($member->contact->dateOfBirth)));
                            }
                        }
                    }
                }
            }

            if (empty($customer->memberships) || $membershipCount === 0) {
                $PHPTemplateLayer->block("NOMEMBERSHIPS");
            }

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);