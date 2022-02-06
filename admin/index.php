<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///UNIVERSAL PAGE CODE: LOAD INCLUDED FILES AND SETUP TEMPLATING///
///*****************************************************************************///

	require("includes/visitickets.php");

	$PHPTemplateLayer = new PHPTemplateLayer();
	$PHPTemplateLayer->prepare($install_path."/templates/index.htm");

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COOKIE PERMISSIONS///
///*****************************************************************************///

		if(!isset($_COOKIE['CookiePermission']))
		{
		$PHPTemplateLayer->assignGlobal("ucookiesenabled","NO");

		$PHPTemplateLayer->block("COOKIES");
		}

	$PHPTemplateLayer->assignGlobal("SETTING_LOGO",SETTING_LOGO);
	$PHPTemplateLayer->assignGlobal("SETTING_TELEPHONE",SETTING_TELEPHONE);
	$PHPTemplateLayer->assignGlobal("SETTING_TITLE",SETTING_TITLE);
	$PHPTemplateLayer->assignGlobal("SETTING_TEXT",SETTING_TEXT);
	$PHPTemplateLayer->assignGlobal("U_YEAR",$U_YEAR);
	$PHPTemplateLayer->assignGlobal("INSTALL_VISITICKETS",INSTALL_VISITICKETS);
	$PHPTemplateLayer->assignGlobal("SETTING_GENERIC_ERROR_MSG",SETTING_GENERIC_ERROR_MSG);
	$PHPTemplateLayer->assignGlobal("SETTING_NAMEREQUIRED", (int) SETTING_NAMEREQUIRED);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: ASSIGN MAIN FONT///
///*****************************************************************************///

		if(CSS_FONT == '"Open Sans", san-serif')
		{
		$googlefonts = 'family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		elseif(CSS_FONT == '"Patua One", serif')
		{
		$googlefonts = 'family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&family=Patua+One';
		}
		elseif(CSS_FONT == '"EB Garamond", serif')
		{
		$googlefonts = 'family=EB+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		elseif(CSS_FONT == '"Luckiest Guy", cursive')
		{
		$googlefonts = 'family=Luckiest+Guy&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		elseif(CSS_FONT == '"Fredoka One", cursive')
		{
		$googlefonts = 'family=Fredoka+One&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		elseif(CSS_FONT == '"Great Vibes", cursive')
		{
		$googlefonts = 'family=Great+Vibes&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		elseif(CSS_FONT == '"Coming Soon", fantasy')
		{
		$googlefonts = 'family=Coming+Soon&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}
		else
		{
		$googlefonts = 'family=Luckiest+Guy&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700';
		}

	$PHPTemplateLayer->assignGlobal("googlefonts",$googlefonts);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: INDEX SEO AND HEAD OPTIONS///
///*****************************************************************************///

	$PHPTemplateLayer->assignGlobal("SETTING_SEO_METATITLE",SETTING_SEO_METATITLE);
	$PHPTemplateLayer->assignGlobal("SETTING_SEO_METADESCRIPTION",SETTING_SEO_METADESCRIPTION);
	$PHPTemplateLayer->assignGlobal("SETTING_SEO_INDEXABLE",SETTING_SEO_INDEXABLE ? "index" : "noindex");
	$PHPTemplateLayer->assignGlobal("SETTING_CODE_ALL",SETTING_CODE_ALL);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: CSS SETTINGS///
///*****************************************************************************///

$PHPTemplateLayer->assignGlobal("CSS_HEADER_BG",CSS_HEADER_BG);
$PHPTemplateLayer->assignGlobal("CSS_HEADER_HOVER",CSS_HEADER_HOVER);
$PHPTemplateLayer->assignGlobal("CSS_HEADER_TEXT",CSS_HEADER_TEXT);
//$PHPTemplateLayer->assignGlobal("CSS_TABS_CART",CSS_TABS_CART);
$PHPTemplateLayer->assignGlobal("CSS_TITLE",CSS_TITLE);
$PHPTemplateLayer->assignGlobal("CSS_LINKS",CSS_LINKS);
$PHPTemplateLayer->assignGlobal("CSS_FONT",CSS_FONT);
$PHPTemplateLayer->assignGlobal("CSS_BUTTON_BG",CSS_BUTTON_BG);
$PHPTemplateLayer->assignGlobal("CSS_BUTTON_TEXT",CSS_BUTTON_TEXT);
$PHPTemplateLayer->assignGlobal("CSS_BUTTON_CONTRAST",CSS_BUTTON_CONTRAST);
$PHPTemplateLayer->assignGlobal("CSS_FOOTER_BG",CSS_FOOTER_BG);
$PHPTemplateLayer->assignGlobal("CSS_FOOTER_TEXT",CSS_FOOTER_TEXT);
$PHPTemplateLayer->assignGlobal("CSS_CALENDAR_ACTIVE",CSS_CALENDAR_ACTIVE);
$PHPTemplateLayer->assignGlobal("CSS_CALENDAR_HOVER",CSS_CALENDAR_HOVER);

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: PAYMENT OPTIONS///
///*****************************************************************************///

		if(in_array('SAGE',SETTING_PAYMENTS))
		{
		$PHPTemplateLayer->block("SAGE");
		}

		if(in_array('APPLE',SETTING_PAYMENTS))
		{
		$PHPTemplateLayer->block("APPLE");
		}

		if(in_array('PAYPAL',SETTING_PAYMENTS))
		{
		$PHPTemplateLayer->block("PAYPAL");
		}

        $PHPTemplateLayer->assignGlobal("confirmd", isset($_SESSION['3dSecure']) ? (int) $_SESSION['3dSecure'] : 0);
        $PHPTemplateLayer->assignGlobal("ordercompleted", isset($_SESSION['order_completed']) ? (int) $_SESSION['order_completed'] : 0);

        if (!empty($_POST)) {
            $PHPTemplateLayer->assignGlobal("postdata",json_encode($_POST));
            unset($_POST);
        } else {
            $PHPTemplateLayer->assignGlobal("postdata",'[]');
        }

    $PHPTemplateLayer->display('','','MINIFY');