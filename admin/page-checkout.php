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
	$PHPTemplateLayer->prepare($install_path."/templates/page-checkout.htm");

    $PHPTemplateLayer->assignGlobal("INSTALL_POSTCODELOOKUPAPIKEY",INSTALL_POSTCODELOOKUPAPIKEY);
    $PHPTemplateLayer->assignGlobal("currentyear",date('Y'));

    $primary_address = customer_primary_address();
    $primary_contact = customer_primary_contact();
    $order_data = $_SESSION['order_data'] ?? [];

    if(SETTING_CAPTURETITLESWITHNAMES)
    {
        $PHPTemplateLayer->block("SHOWTITLE");
    }

    if(SETTING_MARKETINGALLOWED)
    {
        $PHPTemplateLayer->block("SHOWMARKETING");
        $PHPTemplateLayer->assign("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);
    }

    if(!$uloggedin)
    {
        $PHPTemplateLayer->block("SHOWACCOUNTOPTIONS");
        $PHPTemplateLayer->block("LOGINSUGGESTION");
        $PHPTemplateLayer->block("USERTYPE");
        $PHPTemplateLayer->assignGlobal("showcompany",'uhide');
    } else {
        if($primary_contact->companyName)
        {
            $PHPTemplateLayer->assignGlobal("showcompany",'');
            $PHPTemplateLayer->assignGlobal("company",$order_data['companyName'] ?? htmlspecialchars($primary_contact->companyName));
        } else {
            $PHPTemplateLayer->assignGlobal("showcompany",'uhide');
        }
    }

    $PHPTemplateLayer->assignGlobal("addressline1",$order_data['addressLine1'] ?? htmlspecialchars($primary_address->addressLine1));
    $PHPTemplateLayer->assignGlobal("addressline2",$order_data['addressLine2'] ?? htmlspecialchars($primary_address->addressLine2));
    $PHPTemplateLayer->assignGlobal("addressline3",$order_data['addressLine3'] ?? htmlspecialchars($primary_address->addressLine3));
    $PHPTemplateLayer->assignGlobal("postcode",$order_data['postcode'] ?? htmlspecialchars($primary_address->postcode));
    $PHPTemplateLayer->assignGlobal("showaddress",$primary_address->postcode == '' ? 'uhide' : '');

    $selectedCountry = isset($order_data['country']) ? $order_data['country'] : $primary_address->country;

    if (function_exists('countryList') && !empty(countryList())) {
        foreach (countryList() as $key => $name) {
            $PHPTemplateLayer->block("COUNTRY");
            $PHPTemplateLayer->assign("countrykey", $key);
            $PHPTemplateLayer->assign("countryname", $name);
            $PHPTemplateLayer->assign("selected", strtolower($selectedCountry) == strtolower($key) ? 'selected' : '');
        }
    }

    $PHPTemplateLayer->assignGlobal("title",$order_data['title'] ?? htmlspecialchars($primary_contact->title));
    $PHPTemplateLayer->assignGlobal("firstname",$order_data['firstName'] ?? htmlspecialchars($primary_contact->firstName));
    $PHPTemplateLayer->assignGlobal("lastname",$order_data['lastName'] ?? htmlspecialchars($primary_contact->lastName));
    $PHPTemplateLayer->assignGlobal("phone",$order_data['phone'] ?? htmlspecialchars($primary_contact->phone));
    $PHPTemplateLayer->assignGlobal("email",$order_data['email'] ?? htmlspecialchars($primary_contact->email));

    $PHPTemplateLayer->assignGlobal("cardnumber",$order_data['card']['number'] ?? '');
    $PHPTemplateLayer->assignGlobal("cardname",$order_data['card']['name'] ?? '');
    $PHPTemplateLayer->assignGlobal("cardcvv",$order_data['card']['cvv'] ?? '');
    $PHPTemplateLayer->assignGlobal("expirymonth",$order_data['card']['expiry_month'] ?? '');
    $PHPTemplateLayer->assignGlobal("expiryyear",$order_data['card']['expiry_year'] ?? '');

    if ($cart->total > 0) {
        $PHPTemplateLayer->block("PAYMENTS");
    }

    if ($cart->hasMemberships()) {
        $PHPTemplateLayer->block("CREATEACCOUNT");
    } else {
        $PHPTemplateLayer->block("GUESTCHECKOUT");
    }

    $PHPTemplateLayer->assignGlobal("showrequired",!$primary_contact->companyName || ($primary_contact->companyName && SETTING_NAMEREQUIRED) ? '' : 'uhide');

    $response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');

	echo json_encode($response);