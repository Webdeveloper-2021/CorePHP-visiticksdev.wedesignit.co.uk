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

    $cart = new Cart();

    if ($cart->itemCount < 1)
    {
        echo json_encode(['redirect' => 'page-cart.php']);
        die();
    }

    $response = array();

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-checkout-memberships.htm");
    $PHPTemplateLayer->assignGlobal("INSTALL_POSTCODELOOKUPAPIKEY",INSTALL_POSTCODELOOKUPAPIKEY);

	$memberships = $cart->memberships();
	$iteration = 0;

	if (!empty($memberships)) {
	    foreach ($memberships as $membership) {
	        $id = $membership->item->membershipId;

            $PHPTemplateLayer->block("MEMBERSHIP");

            $members = 1;

            $additionalMembers = additionalSessionMembers($membership, $iteration);

            $PHPTemplateLayer->assign('addonsessionmembers', $additionalMembers['html']);

            $PHPTemplateLayer->assign('id', $id);
            $PHPTemplateLayer->assign('iteration', $iteration);
            $PHPTemplateLayer->assign('name', $membership->item->name);
            $PHPTemplateLayer->assign('maxMembers', $membership->item->membership->numberOfPeople);
            $PHPTemplateLayer->assign('members', $members + $additionalMembers['count']);

            if ($membership->item->membership->memberTypes !== 3) {
                $PHPTemplateLayer->block("PRIMARYADDRESS");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $showAddress = false;

                if (isset($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['sameaddress']) && !$_SESSION['memberships_data'][$iteration][$id]['members']['primary']['sameaddress'])
                    $showAddress = true;

                $PHPTemplateLayer->assign('addressline1', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['lookup_first_line'] ?: '');
                $PHPTemplateLayer->assign('addressline2', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['lookup_second_line'] ?: '');
                $PHPTemplateLayer->assign('addressline3', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['lookup_post_town'] ?: '');
                $PHPTemplateLayer->assign('postcode', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['lookup_postcode'] ?: '');
                $PHPTemplateLayer->assign('sameaddresscheckbox', !$showAddress ? 'checked' : '');
                $PHPTemplateLayer->assign('sameaddressshow', !$showAddress ? 'uhide' : '');
            }

            if ($membership->item->membership->primaryMemberNameCaptureType !== 0) {
                $PHPTemplateLayer->block("PRIMARYNAME");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                if ($membership->item->membership->primaryMemberNameCaptureType === 2) {
                    $PHPTemplateLayer->assign('namerequired', 'uhide');
                }

                $PHPTemplateLayer->assign('shown', $membership->item->membership->memberTypes === 3 ? 'uhide' : '');
                $PHPTemplateLayer->assign('samenamecheckbox', isset($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['samename']) && !$_SESSION['memberships_data'][$iteration][$id]['members']['primary']['samename'] ? '' : 'checked');
                $PHPTemplateLayer->assign('samenameshow', isset($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['samename']) && !$_SESSION['memberships_data'][$iteration][$id]['members']['primary']['samename'] ? '' : 'uhide');
                $PHPTemplateLayer->assign('firstname', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['firstName'] ?: '');
                $PHPTemplateLayer->assign('lastname', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['lastName'] ?: '');

                if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                {
                    $PHPTemplateLayer->block("PRIMARYTITLE");
                    $PHPTemplateLayer->assign('iteration', $iteration);
                    $PHPTemplateLayer->assign('id', $id);

                    if ($membership->item->membership->primaryMemberNameCaptureType === 2) {
                        $PHPTemplateLayer->assign('namerequired', 'uhide');
                    }
                }
            }

            if ($membership->item->membership->primaryMemberDateOfBirthCaptureType !== 0) {
                $PHPTemplateLayer->block("PRIMARYDOB");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                if ($membership->item->membership->primaryMemberDateOfBirthCaptureType === 2) {
                    $PHPTemplateLayer->assign('dobrequired', 'uhide');
                }

                if ($membership->item->membership->memberTypes === 3) {
                    for ($i = date('Y'); $i >= 1900; $i--) {
                        $PHPTemplateLayer->block('YEAR');
                        $PHPTemplateLayer->assign('year', $i);
                        $PHPTemplateLayer->assign('selectedyear', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['dob_yyyy'] == $i ? 'selected' : '');

                    }
                } else {
                    for ($i = 1900; $i <= date('Y'); $i++) {
                        $PHPTemplateLayer->block('YEAR');
                        $PHPTemplateLayer->assign('year', $i);
                        $PHPTemplateLayer->assign('selectedyear', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['dob_yyyy'] == $i ? 'selected' : '');
                    }
                }

                for ($i = 1; $i <= 31; $i++) {
                    $PHPTemplateLayer->block('DAY');

                    $day = $i < 10 ? 0 . $i : $i;

                    $PHPTemplateLayer->assign('day', $day);
                    $PHPTemplateLayer->assign('selectedday', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['dob_dd'] == $day ? 'selected' : '');
                }

                for ($i = 1; $i <= 12; $i++) {
                    $PHPTemplateLayer->block('MONTH');

                    $month = $i < 10 ? 0 . $i : $i;

                    $PHPTemplateLayer->assign('month', $month);
                    $PHPTemplateLayer->assign('selectedmonth', $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['dob_mm'] == $month ? 'selected' : '');
                }
            }

            if ($membership->item->membership->primaryMemberPhoneCaptureType !== 0) {
                $PHPTemplateLayer->block( "PRIMARYPHONE");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $billingPhone = '';

                if ($membership->item->membership->memberTypes === 2) {
                    $billingPhone = $_SESSION['order_data']['phone'] ?? '';
                }

                $phone = $billingPhone;

                if ($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['phone'])
                    $phone = $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['phone'];

                $PHPTemplateLayer->assign('phone', $phone);
                $PHPTemplateLayer->assign('billingphone', $billingPhone);

                if ($membership->item->membership->primaryMemberPhoneCaptureType === 2) {
                    $PHPTemplateLayer->assign('phonerequired', 'uhide');
                }
            }

            if ($membership->item->membership->primaryMemberEmailCaptureType !== 0) {
                $PHPTemplateLayer->block("PRIMARYEMAIL");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $billingEmail = '';

                if ($membership->item->membership->memberTypes === 2) {
                    $billingEmail = $_SESSION['order_data']['email'] ?? '';
                }

                $email = $billingEmail;

                if ($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['email'])
                    $email = $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['email'];

                $PHPTemplateLayer->assign('email', $email);
                $PHPTemplateLayer->assign('billingemail', $billingEmail);

                if ($membership->item->membership->primaryMemberEmailCaptureType === 2) {
                    $PHPTemplateLayer->assign('emailrequired', 'uhide');
                }
            }

            if ($membership->item->membership->primaryMemberMarketingCaptureType !== 0) {
                $PHPTemplateLayer->block("PRIMARYMARKETING");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);
            }

            if ($membership->item->membership->additionalMemberAdultNameCaptureType !== 0 || $membership->item->membership->additionalMemberChildNameCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALNAME");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultNameCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultNameCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildNameCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildNameCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('namerequired', implode(' ', $css_class));

                if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                {
                    $PHPTemplateLayer->block("ADDITIONALTITLE");
                    $PHPTemplateLayer->assign('iteration', $iteration);
                    $PHPTemplateLayer->assign('id', $id);
                    $PHPTemplateLayer->assign('namerequired', implode(' ', $css_class));
                }
            }

            if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType !== 0 || $membership->item->membership->additionalMemberChildDateOfBirthCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALDOB");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildDateOfBirthCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildDateOfBirthCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('dobrequired', implode(' ', $css_class));

                if ($membership->item->membership->memberTypes === 3) {
                    for ($i = date('Y'); $i >= 1900; $i--) {
                        $PHPTemplateLayer->block('ADDITIONALYEAR');
                        $PHPTemplateLayer->assign('year', $i);
                    }
                } else {
                    for ($i = 1900; $i <= date('Y'); $i++) {
                        $PHPTemplateLayer->block('ADDITIONALYEAR');
                        $PHPTemplateLayer->assign('year', $i);
                    }
                }
            }

            if ($membership->item->membership->additionalMemberAdultPhoneCaptureType !== 0 || $membership->item->membership->additionalMemberChildPhoneCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALPHONE");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultPhoneCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultPhoneCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildPhoneCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildPhoneCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('phonerequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->additionalMemberAdultEmailCaptureType !== 0 || $membership->item->membership->additionalMemberChildEmailCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALEMAIL");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultEmailCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultEmailCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildEmailCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildEmailCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('emailrequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->additionalMemberAdultMarketingCaptureType !== 0 || $membership->item->membership->additionalMemberChildMarketingCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALMARKETING");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultMarketingCaptureType === 1) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildMarketingCaptureType === 1) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('marketingrequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->memberTypes === 3) {
                $PHPTemplateLayer->block("CHILD");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);

                $PHPTemplateLayer->block("ADDITIONALCHILD");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
            }

            if ($membership->item->membership->numberOfPeople > 1) {
                $PHPTemplateLayer->block("ADDITIONALMEMBER");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
            }

            if ($membership->item->membership->memberTypes === 1) {
                $PHPTemplateLayer->block("ADDITIONALCHILDSELECTION");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
            }

            $selectedCountry = isset($_SESSION['memberships_data'][$iteration][$id]['members']['primary']['country']) ? $_SESSION['memberships_data'][$iteration][$id]['members']['primary']['country'] : '';

            if (function_exists('countryList') && !empty(countryList())) {
                foreach (countryList() as $key => $name) {
                    $PHPTemplateLayer->block("PRIMARYCOUNTRY");
                    $PHPTemplateLayer->assign("countrykey", $key);
                    $PHPTemplateLayer->assign("countryname", $name);
                    $PHPTemplateLayer->assign("selected", strtolower($selectedCountry) == strtolower($key) ? 'selected' : '');
                }
            }

            $iteration++;
        }
    }

	$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);

function additionalSessionMembers($membership, $iteration) {
    global $install_path;

    $PHPTemplateLayer = new PHPTemplateLayer();
    $PHPTemplateLayer->prepare($install_path."/templates/page-checkout-memberships-addon-members.htm");

    $id = $membership->item->membershipId;
    $count = 0;

    if (isset($_SESSION['memberships_data'][$iteration][$id]['members'])) {
        foreach ($_SESSION['memberships_data'][$iteration][$id]['members'] as $type => $member) {
            if ($type === 'primary')
                continue;

            $count++;

            $number = explode('_', $type)[1];

            $PHPTemplateLayer->block("ADDONMEMBER");
            $PHPTemplateLayer->assign('iteration', $iteration);
            $PHPTemplateLayer->assign('id', $id);

            if ($membership->item->membership->additionalMemberAdultNameCaptureType !== 0 || $membership->item->membership->additionalMemberChildNameCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALNAME");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultNameCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultNameCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildNameCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildNameCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('namerequired', implode(' ', $css_class));

                $PHPTemplateLayer->assign('firstname', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['firstName'] ?: '');
                $PHPTemplateLayer->assign('lastname', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['lastName'] ?: '');

                if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                {
                    $PHPTemplateLayer->block("ADDITIONALTITLE");
                    $PHPTemplateLayer->assign('iteration', $iteration);
                    $PHPTemplateLayer->assign('id', $id);
                    $PHPTemplateLayer->assign('namerequired', implode(' ', $css_class));
                    $PHPTemplateLayer->assign('number', $number);

                    $PHPTemplateLayer->assign('title', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['title'] ?: '');
                }
            }

            if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType !== 0 || $membership->item->membership->additionalMemberChildDateOfBirthCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALDOB");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);

                $css_class = $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['isChild'] ? [] : ['uhide'];

                if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultDateOfBirthCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildDateOfBirthCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildDateOfBirthCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('dobrequired', implode(' ', $css_class));

                if ($membership->item->membership->memberTypes === 3) {
                    for ($i = date('Y'); $i >= 1900; $i--) {
                        $PHPTemplateLayer->block('ADDITIONALYEAR');
                        $PHPTemplateLayer->assign('year', $i);
                        $PHPTemplateLayer->assign('selectedyear', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['dob_yyyy'] == $i ? 'selected' : '');
                    }
                } else {
                    for ($i = 1900; $i <= date('Y'); $i++) {
                        $PHPTemplateLayer->block('ADDITIONALYEAR');
                        $PHPTemplateLayer->assign('year', $i);
                        $PHPTemplateLayer->assign('selectedyear', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['dob_yyyy'] == $i ? 'selected' : '');
                    }
                }

                for ($i = 1; $i <= 31; $i++) {
                    $PHPTemplateLayer->block('DAY');

                    $day = $i < 10 ? 0 . $i : $i;

                    $PHPTemplateLayer->assign('day', $day);
                    $PHPTemplateLayer->assign('selectedday', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['dob_dd'] == $day ? 'selected' : '');
                }

                for ($i = 1; $i <= 12; $i++) {
                    $PHPTemplateLayer->block('MONTH');

                    $month = $i < 10 ? 0 . $i : $i;

                    $PHPTemplateLayer->assign('month', $month);
                    $PHPTemplateLayer->assign('selectedmonth', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['dob_mm'] == $month ? 'selected' : '');
                }
            }

            if ($membership->item->membership->additionalMemberAdultPhoneCaptureType !== 0 || $membership->item->membership->additionalMemberChildPhoneCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALPHONE");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultPhoneCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultPhoneCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildPhoneCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildPhoneCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('phonerequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->additionalMemberAdultEmailCaptureType !== 0 || $membership->item->membership->additionalMemberChildEmailCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALEMAIL");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultEmailCaptureType === 1) {
                    $css_class[] = 'adult-required';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberAdultEmailCaptureType === 2) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildEmailCaptureType === 1) $css_class[] = 'child-required';
                if ($membership->item->membership->additionalMemberChildEmailCaptureType === 2) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('emailrequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->additionalMemberAdultMarketingCaptureType !== 0 || $membership->item->membership->additionalMemberChildMarketingCaptureType !== 0) {
                $PHPTemplateLayer->block("ADDITIONALMARKETING");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);
                $PHPTemplateLayer->assign('number', $number);

                $css_class = ['uhide'];

                if ($membership->item->membership->additionalMemberAdultMarketingCaptureType === 1) {
                    $css_class[] = 'adult-optional';
                    unset($css_class[0]);
                }

                if ($membership->item->membership->additionalMemberChildMarketingCaptureType === 1) $css_class[] = 'child-optional';

                $PHPTemplateLayer->assign('marketingrequired', implode(' ', $css_class));
            }

            if ($membership->item->membership->memberTypes === 3) {
                $PHPTemplateLayer->block("ADDITIONALCHILD");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);
            }

            if ($membership->item->membership->memberTypes === 1) {
                $PHPTemplateLayer->block("ADDITIONALCHILDSELECTION");
                $PHPTemplateLayer->assign('iteration', $iteration);
                $PHPTemplateLayer->assign('id', $id);
                $PHPTemplateLayer->assign('number', $number);
                $PHPTemplateLayer->assign('ischildchecked', $_SESSION['memberships_data'][$iteration][$id]['members'][$type]['isChild'] ? ' checked' : '');

            }
        }
    }

    return [
        'html' => $PHPTemplateLayer->display('VARIABLE','','MINIFY'),
        'count' => $count
    ];
}