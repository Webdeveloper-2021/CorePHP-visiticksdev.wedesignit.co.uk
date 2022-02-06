<?php

///*****************************************************************************///
///UNIVERSAL PAGE CODE: COPYRIGHT NOTICE?///
///*****************************************************************************///
///*****************************************************************************///
///INDIVIDUAL PAGE CODE: SESSION AND UNIVERSAL VARIABLES///
///*****************************************************************************///
///
//

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: INCLUDE FILES///
///*****************************************************************************///

    use includes\classes\Cache;

    require_once("functions/utilities.php");
    require_once("functions/forms.php");
    require_once("functions/connect.php");
    require_once("classes/PHPTemplateLayer-1.3.0/class.PHPTemplateLayer.inc.php");
    require_once("functions/admin.php");

    if (session_status() === PHP_SESSION_NONE) {
        $params = session_get_cookie_params();

        // set as your own needs:
        $maxlifetime = $params['lifetime'];
        $path = $params['path'];
        $domain = $params['domain'];
        $secure = true;
        $httponly = false;
        $samesite = 'none';

        if(PHP_VERSION_ID < 70300) {
            session_set_cookie_params($maxlifetime, $path.'; samesite='.$samesite, $domain, $secure, $httponly);
        } else {
            session_set_cookie_params([
                'lifetime' => $maxlifetime,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite
                ]
            );
        }

        session_start();
    }

	$U_DATETIME = gmdate("Y-m-d H:i:s"); ///The current DATETIME in UTC time///
	$U_DATE = gmdate("Y-m-d");
	$U_YEAR = gmdate("Y");

    $U_SESSION_CART_ID = $_SESSION["cart_id"] ?? null;
    $U_SESSION_API_TOKEN = $_SESSION["api_token"] ?? null;
    $U_SESSION_CUSTOMER_ID = $_SESSION["customer_id"] ?? null;
    $U_SESSION_CUSTOMER_EMAIL = $_SESSION["customer_email"] ?? null;

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: LOGIN STATUS///
///*****************************************************************************///

    $uloggedin = false;

		if($U_SESSION_CUSTOMER_ID && $U_SESSION_CUSTOMER_ID != "")
		{
		$uloggedin = true;
		}

///*****************************************************************************///
///INDIVIDUAL PAGE CODE: GLOBAL SITE VARIABLES///
///*****************************************************************************///

    $U_SESSION_API_TOKEN = Cache::get('apiToken');

    $globalSettings = getGlobalSettings();

    $payments = [];
    if (isset($globalSettings['SagePayEnabled']) && $globalSettings['SagePayEnabled']) $payments[] = 'SAGE';
    if (isset($globalSettings['PayPalEnabled']) && $globalSettings['PayPalEnabled']) $payments[] = 'PAYPAL';
    if (isset($globalSettings['ApplePayEnabled']) && $globalSettings['ApplePayEnabled']) $payments[] = 'APPLE';

	define('INSTALL_POSTCODELOOKUPAPIKEY', $globalSettings['IdealPostCodeAPIKey'] ?? null);
	define('SETTING_LOGS', $globalSettings['LoggingEnabled'] ?? 1);
	define('SETTING_LOGO', isset($globalSettings['LogoFileName']) ? API_URL."images/".$globalSettings['LogoFileName'] : null);
	define('SETTING_TELEPHONE', $globalSettings['TelephoneNumber'] ?? null);
	define('SETTING_PAYMENTS', $payments);
	define('SETTING_TITLE', $globalSettings['AttractionName'] ?? null);
	define('SETTING_TEXT', $globalSettings['PageFooterText'] ?? null);
	define('SETTING_OFFER_ENABLED', $globalSettings['CheckoutAddMoreItemsEnabled'] ?? null);
	define('SETTING_OFFER_TEXT', $globalSettings['CheckoutAddMoreItemsText'] ?? null);
	define('SETTING_OFFER_LINK', $globalSettings['CheckoutAddMoreItemsCategoryId'] ?? null);
	define('SETTING_CONFIRMATION', $globalSettings['OrderConfirmationText'] ?? null);
	define('SETTING_MEMBERSHIP_CONFIRMATION', $globalSettings['MembershipOrderConfirmationText'] ?? null);
	define('SETTING_PAGE_TERMS', $globalSettings['TermsPageContent'] ?? null);
	define('SETTING_PAGE_PRIVACY', $globalSettings['PrivacyPageContent'] ?? null);
	define('SETTING_PAGE_REFUNDS', $globalSettings['RefundPolicyPageContent'] ?? null);
	define('SETTING_PAGE_COOKIES', $globalSettings['CookiePolicyPageContent'] ?? null);
	define('SETTING_SEO_INDEXABLE', $globalSettings['SEOIndexableEnabled'] ?? null);
	define('SETTING_SEO_METATITLE', $globalSettings['SEOMetaTitle'] ?? null);
	define('SETTING_SEO_METADESCRIPTION', $globalSettings['SEOMetaDescription'] ?? null);
	define('SETTING_CODE_ALL', $globalSettings['TrackingJavascript'] ?? null);
	define('SETTING_CODE_CONFIRMATION', $globalSettings['LeadConversionTrackingJavascript'] ?? null);
	define('SETTING_MARKETINGTEXT', $globalSettings['MarketingText'] ?? null);
	define('SETTING_DEFAULTIMAGE', isset($globalSettings['DefaultImageFileName']) ? API_URL."images/".$globalSettings['DefaultImageFileName'] : null);
	define('SETTING_HOME_CATEGORY_TEXT', $globalSettings['HomeCategoryText'] ?? null);
	define('SETTING_HOME_CATEGORY_IMAGE', $globalSettings['HomeCategoryImageFileName'] ?? null);
	define('SETTING_HOME_CATEGORY_BUTTON', $globalSettings['HomeCategoryButtonText'] ?? null);
	define('SETTING_HOME_EVENT_TEXT', $globalSettings['HomeEventText'] ?? null);
	define('SETTING_HOME_EVENT_IMAGE', $globalSettings['HomeEventImageFile'] ?? null);
	define('SETTING_HOME_EVENT_BUTTON', $globalSettings['HomeEventButtonText'] ?? null);
	define('SETTING_CAPTURETITLESWITHNAMES', $globalSettings['CaptureTitlesWithNames'] ?? null);
	define('SETTING_CAPTURETITLESWITHMEMBERNAMES', $globalSettings['CaptureTitlesWithMemberNames'] ?? null);
	define('SETTING_MARKETINGALLOWED', $globalSettings['CaptureMarketingAllowed'] ?? null);
	define('CSS_HEADER_BG', $globalSettings['HeaderBackgroundColour'] ?? null);
	define('CSS_HEADER_HOVER', $globalSettings['HeaderHoverColour'] ?? null);
	define('CSS_HEADER_TEXT', $globalSettings['HeaderTextColour'] ?? null);
	define('CSS_TITLE', $globalSettings['MainTitleColour'] ?? null);
	define('CSS_LINKS', $globalSettings['LinkColour'] ?? null);
	define('CSS_FONT', $globalSettings['MainTitleFont'] ?? null);
	define('CSS_BUTTON_BG', $globalSettings['ButtonBackgroundColour'] ?? null);
	define('CSS_BUTTON_CONTRAST', $globalSettings['ButtonContrast'] ?? null);
	define('CSS_BUTTON_TEXT', $globalSettings['ButtonTextColour'] ?? null);
	define('CSS_FOOTER_BG', $globalSettings['FooterBackgroundColour'] ?? null);
	define('CSS_FOOTER_TEXT', $globalSettings['FooterTextColour'] ?? null);
	define('CSS_CALENDAR_ACTIVE', $globalSettings['CalendarActiveColour'] ?? null);
	define('CSS_CALENDAR_HOVER', $globalSettings['CalendarHoverColour'] ?? null);
	define('SETTING_GENERIC_ERROR_MSG', $globalSettings['GenericErrorMessage'] ?? null);
	define('SETTING_PASSTITLE_SINGLE', $globalSettings['MembershipPassName'] ?? null);
	define('SETTING_PASSTITLE_PLURAL', $globalSettings['MembershipPassNamePlural'] ?? null);
	define('SETTING_NAMEREQUIRED', $globalSettings['NameMandatoryForCompanyAccount'] ?? null);
	define('SETTING_TAB_CATEGORIES', $globalSettings['CategoriesTabText'] ?? null);
	define('SETTING_TAB_CALENDAR', $globalSettings['CalendarTabText'] ?? null);
	define('SETTING_GIFTAID_TITLE', $globalSettings['GiftAidTitleText'] ?? null);
	define('SETTING_GIFTAID_TEXT', $globalSettings['GiftAidText'] ?? null);
	define('SETTING_GIFTAID_YES', $globalSettings['GiftAidYesText'] ?? null);
	define('SETTING_GIFTAID_NO', $globalSettings['GiftAidNoText'] ?? null);
	define('PAYPAL_ENVIRONMENT', $globalSettings['PayPalEnvironment'] ?? 'production'); // production, sandbox
	define('PAYPAL_CLIENT_ID', $globalSettings['PayPalClientID'] ?? null);
	define('PAYPAL_CLIENT_SECRET', $globalSettings['PayPalClientSecret'] ?? null);
	define('SETTING_CHILDMAXAGE', $globalSettings['ChildMaxAge'] ?? 12);
	define('SETTING_MIN_MEMBERSHIP_RENEWAL_DAYS', $globalSettings['MinimumMembershipRenewalDays'] ?? 30);
	define('SETTING_CHECKOUTBASKETEXTEND', $globalSettings['CheckOutBasketExpirationAddMinutes'] ?? 5);
	define('SETTING_BASKETEXPIRATION', $globalSettings['BasketExpirationMinutes'] ?? 15);
	define('OPAYO_VENDORNAME', $globalSettings['OpayoVendorName'] ?? null);
	define('OPAYO_TESTMODE', $globalSettings['OpayoTestMode'] ?? 0);
	define('OPAYO_ENABLED', $globalSettings['SagePayEnabled'] ?? null);
	define('PAYPAL_ENABLED', $globalSettings['PayPalEnabled'] ?? null);
	define('APPLE_ENABLED', $globalSettings['ApplePayEnabled'] ?? null);
	define('SETTING_EMAIL_ORDERCONFIRMATION_SUBJECT', $globalSettings['OrderConfirmationEmailSubject'] ?? null);
	define('SETTING_EMAIL_ORDERCONFIRMATION_BODY', $globalSettings['OrderConfirmationEmailBody'] ?? null);
	define('SETTING_EMAIL_ORDERCONFIRMATION_LINE', $globalSettings['OrderConfirmationEmailLine'] ?? null);
	define('SETTING_EMAIL_RESETPASS_SUBJECT', $globalSettings['ResetPasswordEmailSubject'] ?? null);
	define('SETTING_EMAIL_RESETPASS_BODY', $globalSettings['ResetPasswordEmailBody'] ?? null);
	define('SETTING_TANDCREQUIRED', $globalSettings['TermsConfirmationRequiredOnCheckout'] ?? null);
	define('SETTING_TANDCTEXT', $globalSettings['TermsConfirmationSummary'] ?? null);
