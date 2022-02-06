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
    use includes\classes\models\Order;

    $cart = new Cart;
    $order = new Order;

    if ($cart->itemCount < 1)
    {
        echo json_encode(['redirect' => 'page-cart.php']);
        die();
    }

    $error = false;
    $invalid = [];
    $response = array(
        "completed" => "",
        "error"     => ""
    );

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/page-checkout-memberships.htm");

	$membershipsPOST = $_POST['memberships'];

	$order_memberships = [];

    $memberships = $cart->memberships();
    $iteration = 0;

    $_SESSION['memberships_data'] = $membershipsPOST;

    $error_msg = "Please correct the errors highlighted above.";
    $processed = [];

if (!empty($memberships) && !empty($membershipsPOST)) {
    $total_members = 0;

    foreach ($memberships as $membership) {
        $membershipSettings = $membership->item->membership;

        foreach ($membershipsPOST as $iteration => $mship) {
            $membershipId = key($mship);

            if ($membershipSettings->id !== $membershipId || in_array($membershipId . '-' . $iteration, $processed)) // TODO: rethink this
                continue;

            $processed[] = $membershipId . '-' . $iteration;

            $membership_info = [];
            $error = false;
            $primary = false;

            if ($membershipSettings->numberOfPeople < count($mship[$membershipId]['members'])) {
                $error = true;
                $error_msg = "Too many people added.";
            }

            $membership_info['membershipId'] = $membershipId;
            $adults = 0;
            $children = 0;
            $member_error_msg = '';

            foreach ($mship[$membershipId]['members'] as $k => $member) {
                $total_members++;

                if ($k === 'primary') {
                    $primary = true;

                    if (!$member['samename']) {
                        if ($membershipSettings->primaryMemberNameCaptureType === 1) {
                            if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                            {
                                if (!isset($member['title']) || empty($member['title'])) {
                                    $error = true;
                                    $response['invalidFields']["title_" . $membershipId . "_" . $iteration] = "error";
                                }
                            }

                            if (!isset($member['firstName']) || empty($member['firstName'])) {
                                $error = true;
                                $response['invalidFields']["firstname_" . $membershipId . "_" . $iteration] = "error";
                            }

                            if (!isset($member['lastName']) || empty($member['lastName'])) {
                                $error = true;
                                $response['invalidFields']["lastname_" . $membershipId . "_" . $iteration] = "error";
                            }
                        }
                    }

                    if (!$member['sameaddress'] && !$member['isChild']) {
                        if (!isset($member['lookup_first_line']) || empty($member['lookup_first_line'])) {
                            $error = true;
                            $response['invalidFields']["lookup_first_line_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (!isset($member['lookup_post_town']) || empty($member['lookup_post_town'])) {
                            $error = true;
                            $response['invalidFields']["lookup_post_town_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (!isset($member['lookup_postcode']) || empty($member['lookup_postcode'])) {
                            $error = true;
                            $response['invalidFields']["lookup_postcode_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (!isset($member['country']) || empty($member['country'])) {
                            $error = true;
                            $response['invalidFields']["country_" . $membershipId . "_" . $iteration] = "error";
                        }
                    }

                    if ($membershipSettings->primaryMemberDateOfBirthCaptureType === 1) {
                        if (!isset($member['dob_dd']) || empty($member['dob_dd']) || !number_between($member['dob_dd'], 1, 31)) {
                            $error = true;
                            $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (!isset($member['dob_mm']) || empty($member['dob_mm']) || !number_between($member['dob_mm'], 1, 12)) {
                            $error = true;
                            $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (!isset($member['dob_yyyy']) || empty($member['dob_yyyy']) || !number_between($member['dob_yyyy'], 1900, date('Y'))) {
                            $error = true;
                            $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration] = "error";
                        }

                        if (isset($member['isChild']) && $member['isChild']) {
                            if (isset($member['dob_dd']) && !empty($member['dob_dd']) && isset($member['dob_mm']) && !empty($member['dob_mm']) && isset($member['dob_yyyy']) && !empty($member['dob_yyyy'])) {
                                if (checkdate((int)$member['dob_mm'], (int)$member['dob_dd'], (int)$member['dob_yyyy'])) {
                                    $date1 = new DateTime($member['dob_yyyy'] . "-" . $member['dob_mm'] . "-" . $member['dob_dd']);
                                    $date2 = new DateTime("now");
                                    $interval = $date2->diff($date1);
                                    $leap_years = leapYearCount($member['dob_yyyy'], date('Y'));

                                    if ($interval->days > SETTING_CHILDMAXAGE * 365 + $leap_years) {
                                        $error = true;
                                        $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration] = "error";
                                        $member_error_msg = "Member is too old to qualify as a child.";
                                    }
                                } else {
                                    $error = true;
                                    $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration] = "error";
                                    $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration] = "error";
                                    $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration] = "error";
                                    $member_error_msg = "One of the dates you entered is not a valid date.";
                                }
                            }
                        }
                    }

                    if ($membershipSettings->primaryMemberPhoneCaptureType === 1) {
                        if (!isset($member['phone']) || empty($member['phone'])) {
                            $error = true;
                            $response['invalidFields']["phone_" . $membershipId . "_" . $iteration] = "error";
                        }
                    }

                    if ($membershipSettings->primaryMemberEmailCaptureType === 1) {
                        if (!isset($member['email']) || empty($member['email']) || !form_valid_email($member['email'])) {
                            $error = true;
                            $response['invalidFields']["email_" . $membershipId . "_" . $iteration] = "error";
                        }
                    } else {
                        if (isset($member['email']) && !empty($member['email']) && !form_valid_email($member['email'])) {
                            $error = true;
                            $response['invalidFields']["email_" . $membershipId . "_" . $iteration] = "error";
                        }
                    }

                    if(isset($member['isChild']) && $member['isChild'])
                        $children++;
                    else
                        $adults++;
                } else {
                    if (isset($member['isChild']) && $member['isChild']) {
                        if ($membershipSettings->additionalMemberChildNameCaptureType === 1) {
                            if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                            {
                                if (!isset($member['title']) || empty($member['title'])) {
                                    $error = true;
                                    $response['invalidFields']["title_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                }
                            }

                            if (!isset($member['firstName']) || empty($member['firstName'])) {
                                $error = true;
                                $response['invalidFields']["firstname_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['lastName']) || empty($member['lastName'])) {
                                $error = true;
                                $response['invalidFields']["lastname_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        if ($membershipSettings->additionalMemberChildDateOfBirthCaptureType === 1) {
                            if (!isset($member['dob_dd']) || empty($member['dob_dd']) || !number_between($member['dob_dd'], 1, 31)) {
                                $error = true;
                                $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['dob_mm']) || empty($member['dob_mm']) || !number_between($member['dob_mm'], 1, 12)) {
                                $error = true;
                                $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['dob_yyyy']) || empty($member['dob_yyyy']) || !number_between($member['dob_yyyy'], 1900, date('Y'))) {
                                $error = true;
                                $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (isset($member['isChild']) && $member['isChild']) {
                                if (isset($member['dob_dd']) && !empty($member['dob_dd']) && isset($member['dob_mm']) && !empty($member['dob_mm']) && isset($member['dob_yyyy']) && !empty($member['dob_yyyy'])) {
                                    if (checkdate((int)$member['dob_mm'], (int)$member['dob_dd'], (int)$member['dob_yyyy'])) {
                                        $date1 = new DateTime($member['dob_yyyy'] . "-" . $member['dob_mm'] . "-" . $member['dob_dd']);
                                        $date2 = new DateTime("now");
                                        $interval = $date2->diff($date1);
                                        $leap_years = leapYearCount($member['dob_yyyy'], date('Y'));

                                        if ($interval->days > SETTING_CHILDMAXAGE * 365 + $leap_years) {
                                            $error = true;
                                            $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                            $member_error_msg = "Member is too old to qualify as a child.";
                                        }
                                    } else {
                                        $error = true;
                                        $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $member_error_msg = "One of the dates you entered is not a valid date.";
                                    }
                                }
                            }
                        }

                        if ($membershipSettings->additionalMemberChildPhoneCaptureType === 1) {
                            if (!isset($member['phone']) || empty($member['phone'])) {
                                $error = true;
                                $response['invalidFields']["phone_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        if ($membershipSettings->additionalMemberChildEmailCaptureType === 1) {
                            if (!isset($member['email']) || empty($member['email']) || !form_valid_email($member['email'])) {
                                $error = true;
                                $response['invalidFields']["email_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        $children++;
                    } else {
                        if ($membershipSettings->additionalMemberAdultNameCaptureType === 1) {
                            if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                            {
                                if (!isset($member['title']) || empty($member['title'])) {
                                    $error = true;
                                    $response['invalidFields']["title_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                }
                            }

                            if (!isset($member['firstName']) || empty($member['firstName'])) {
                                $error = true;
                                $response['invalidFields']["firstname_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['lastName']) || empty($member['lastName'])) {
                                $error = true;
                                $response['invalidFields']["lastname_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        if ($membershipSettings->additionalMemberAdultDateOfBirthCaptureType === 1) {
                            if (!isset($member['dob_dd']) || empty($member['dob_dd']) || !number_between($member['dob_dd'], 1, 31)) {
                                $error = true;
                                $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['dob_mm']) || empty($member['dob_mm']) || !number_between($member['dob_mm'], 1, 12)) {
                                $error = true;
                                $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (!isset($member['dob_yyyy']) || empty($member['dob_yyyy']) || !number_between($member['dob_yyyy'], 1900, date('Y'))) {
                                $error = true;
                                $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }

                            if (isset($member['isChild']) && $member['isChild']) {
                                if (isset($member['dob_dd']) && !empty($member['dob_dd']) && isset($member['dob_mm']) && !empty($member['dob_mm']) && isset($member['dob_yyyy']) && !empty($member['dob_yyyy'])) {
                                    if (checkdate((int)$member['dob_mm'], (int)$member['dob_dd'], (int)$member['dob_yyyy'])) {
                                        $date1 = new DateTime($member['dob_yyyy'] . "-" . $member['dob_mm'] . "-" . $member['dob_dd']);
                                        $date2 = new DateTime("now");
                                        $interval = $date2->diff($date1);
                                        $leap_years = leapYearCount($member['dob_yyyy'], date('Y'));

                                        if ($interval->days > SETTING_CHILDMAXAGE * 365 + $leap_years) {
                                            $error = true;
                                            $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                            $member_error_msg = "Member is too old to qualify as a child.";
                                        }
                                    } else {
                                        $error = true;
                                        $response['invalidFields']["dob_dd_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $response['invalidFields']["dob_mm_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $response['invalidFields']["dob_yyyy_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                                        $member_error_msg = "One of the dates you entered is not a valid date.";
                                    }
                                }
                            }
                        }

                        if ($membershipSettings->additionalMemberAdultPhoneCaptureType === 1) {
                            if (!isset($member['phone']) || empty($member['phone'])) {
                                $error = true;
                                $response['invalidFields']["phone_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        if ($membershipSettings->additionalMemberAdultEmailCaptureType === 1) {
                            if (!isset($member['email']) || empty($member['email']) || !form_valid_email($member['email'])) {
                                $error = true;
                                $response['invalidFields']["email_" . $membershipId . "_" . $iteration . "_" . $k] = "error";
                            }
                        }

                        $adults++;
                    }
                }

                $dob = null;

                if ((isset($member['dob_dd']) && !empty($member['dob_dd'])) && (isset($member['dob_mm']) && !empty($member['dob_mm'])) && (isset($member['dob_yyyy']) && !empty($member['dob_yyyy']))) {
                    $dob = $member['dob_yyyy'] . '-' . $member['dob_mm'] . '-' . $member['dob_dd'];
                }

                $marketing = isset($member['marketing']);

                if (isset($member['isChild'])) $marketing = false;
                if (isset($member['sameaddress'])) $marketing = $_SESSION['order_data']['marketingAllowed"'];

                $data = [
                    'firstName'     => $member['samename'] ? $_SESSION['order_data']['firstName'] : $member['firstName'],
                    'lastName'      => $member['samename'] ? $_SESSION['order_data']['lastName'] : $member['lastName'],
                    'email'         => $member['email'] ?? '',
                    'phone'         => $member['phone'] ?? '',
                    'child'         => isset($member['isChild']),
                    'dateOfBirth'   => $dob,
                    'marketing'     => $marketing,
                    'primaryMember' => $primary
                ];

                if (SETTING_CAPTURETITLESWITHMEMBERNAMES)
                {
                    $data['title'] = $member['samename'] ? $_SESSION['order_data']['title'] : $member['title'];
                }

                $data = array_filter($data, 'strlen');

                $membership_info['members'][] = $data;

                if ($primary) {
                    $membership_info['addressLine1'] = $member['sameaddress'] ? $_SESSION['order_data']['addressLine1'] : $member['lookup_first_line'];
                    $membership_info['addressLine2'] = $member['sameaddress'] ? $_SESSION['order_data']['addressLine2'] : $member['lookup_second_line'];
                    $membership_info['addressLine3'] = $member['sameaddress'] ? $_SESSION['order_data']['addressLine3'] : $member['lookup_post_town'];
                    $membership_info['postcode'] = $member['sameaddress'] ? $_SESSION['order_data']['postcode'] : $member['lookup_postcode'];
                    $membership_info['country'] = $member['sameaddress'] ? $_SESSION['order_data']['country'] : $member['country'];
                }
            }

            if ($membershipSettings->maximumAdults < $adults) {
                $error = true;
                $error_msg = "Too many adults added.";
            }

            if ($membershipSettings->maximumChildren < $children) {
                $error = true;
                $error_msg = "Too many children added.";
            }

            if ($error) {
                $response['formError'][$membershipId . "_" . $iteration] = $member_error_msg ?: $error_msg;
                $invalid[$membershipId . "_" . $iteration] = true;
            }

            $order_memberships[] = $membership_info;
        }
    }
}

if (empty($invalid)) {
    $order_data = $_SESSION['order_data'];
    $order_data['memberships'] = $order_memberships;

    $free_tickets = 0;

    foreach ($cart->items as $cartItem) {
        if ($cartItem->item->membershipEventDiscountedItem)
            $free_tickets += $cartItem->quantity;
    }

    if ($total_members < $free_tickets) {
        $response["error"] = "You haven’t added enough memberships to your " . SETTING_PASSTITLE_PLURAL . " for the selected number of tickets.";
    } else {
        if ($order->create($order_data, true)) {
            $_SESSION['order_data'] = $order_data;

            $response["completed"] = true;
        } else {
            $errors = implode('<br>', $order->errors);
            $response["error"] = $errors;
        }
    }

    $response["content"] = $PHPTemplateLayer->display('VARIABLE', '', 'MINIFY');
}

	echo json_encode($response);