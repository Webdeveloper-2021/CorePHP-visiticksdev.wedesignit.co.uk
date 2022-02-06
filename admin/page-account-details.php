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
	$PHPTemplateLayer->prepare($install_path."/templates/page-account-details.htm");

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: REDIRECT IF NOT LOGGED IN///
///*****************************************************************************///

		if(!$uloggedin)
		{
		$response["redirect"] = "page-register.php";
		}
		else
		{
        $PHPTemplateLayer->assignGlobal("INSTALL_POSTCODELOOKUPAPIKEY",INSTALL_POSTCODELOOKUPAPIKEY);
        $PHPTemplateLayer->assignGlobal("SETTING_MARKETINGTEXT",SETTING_MARKETINGTEXT);

        $primary_address = customer_primary_address();
        $PHPTemplateLayer->assignGlobal("addressline1",$primary_address->addressLine1);
        $PHPTemplateLayer->assignGlobal("addressline2",$primary_address->addressLine2);
        $PHPTemplateLayer->assignGlobal("addressline3",$primary_address->addressLine3);
        $PHPTemplateLayer->assignGlobal("postcode",$primary_address->postcode);

        if (function_exists('countryList') && !empty(countryList())) {
            foreach (countryList() as $key => $name) {
                $PHPTemplateLayer->block("COUNTRY");
                $PHPTemplateLayer->assign("countrykey", $key);
                $PHPTemplateLayer->assign("countryname", $name);
                $PHPTemplateLayer->assign("selected", strtolower($primary_address->country) == strtolower($key) ? 'selected' : '');
            }
        }

        $primary_contact = customer_primary_contact();
        $PHPTemplateLayer->assignGlobal("firstname",$primary_contact->firstName);
        $PHPTemplateLayer->assignGlobal("lastname",$primary_contact->lastName);
        $PHPTemplateLayer->assignGlobal("phone",$primary_contact->phone);
        $PHPTemplateLayer->assignGlobal("email",$primary_contact->email);

        if($primary_contact->companyName)
        {
            $PHPTemplateLayer->block("COMPANY");
            $PHPTemplateLayer->assign("company",$primary_contact->companyName);
        }

        if(SETTING_CAPTURETITLESWITHNAMES)
        {
            $PHPTemplateLayer->block("SHOWTITLE");
            $PHPTemplateLayer->assign("title",$primary_contact->title);
        }

        if(SETTING_MARKETINGALLOWED)
        {
            if ($primary_contact->marketingAllowed)
            {
                $PHPTemplateLayer->block("SHOWMARKETINGCHECKED");
            }
            else
            {
                $PHPTemplateLayer->block("SHOWMARKETING");
            }
        }

        $PHPTemplateLayer->assignGlobal("showrequired",$primary_contact->companyName && SETTING_NAMEREQUIRED ? '' : 'uhide');

		$response["content"] = $PHPTemplateLayer->display('VARIABLE','','MINIFY');
        }

    echo json_encode($response);