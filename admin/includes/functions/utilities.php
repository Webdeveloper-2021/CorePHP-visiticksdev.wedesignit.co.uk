<?php

use includes\classes\API;
use includes\classes\Cache;

	function run_logs_create($date, $datetime, $install_type, $install_version, $requesturl, $requesttype, $requestbody, $responsecode, $responsebody)
	{
	$logfilename = "logs/".$date.".txt";

	$logfile = fopen($logfilename, "a");

	fwrite($logfile,"\r\rDatetime: ".$datetime);
	fwrite($logfile,"\rInstall Type: ".$install_type);
	fwrite($logfile,"\rInstall Version: ".$install_version);
	fwrite($logfile,"\rRequest URL: ".$requesturl);
	fwrite($logfile,"\rRequest Type: ".$requesttype);
	fwrite($logfile,"\rRequest Body: ".json_encode($requestbody, JSON_UNESCAPED_SLASHES));
	fwrite($logfile,"\rResponse Code:".$responsecode);
	fwrite($logfile,"\rResponse Body:".$responsebody);

	fclose($logfile);
	}

///*****************************************************************************///

	function get_content_shortversion($string,$limit)
	{
		if(strlen($string) > $limit) 
		{
       		preg_match('/(.{' . $limit . '}.*?)\b/', $string, $matches);
		$string = rtrim($matches[1])."...";
		}

	return $string;
	}

///*****************************************************************************///

	function get_content_oddeven($number)
	{
    return $number % 2 == 0 ? "even" : "odd";
	}

///*****************************************************************************///

	function process_content_displayOrder($a,$b)
	{
	return strcmp($a->name, $b->name);
	}

///*****************************************************************************///

	function process_bolean_checkbox($string)
	{
    return in_array(strtolower($string), array("yes", "true", "on", "1"));
	}

///*****************************************************************************///

	function process_member_login($customer,$email)
	{
	$_SESSION["customer_id"] = $customer->id;
	$_SESSION["customer_email"] = $email;
	$_SESSION["customer"] = $customer;
	}

///*****************************************************************************///

    function base_url()
    {
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
    }

///*****************************************************************************///

    function dd(...$var)
    {
    foreach ($var as $v) {
        var_dump($v);
    }
    die();
    }

///*****************************************************************************///

    function show_prices_without_donation()
    {
    return isset($_SESSION['prices_without_donation']) && $_SESSION['prices_without_donation'] === true;
    }

///*****************************************************************************///

    function toggle_donation_prices()
    {
    return $_SESSION['prices_without_donation'] = ! $_SESSION['prices_without_donation'];
    }

///*****************************************************************************///

    function customer_primary_address() {
	    if (isset($_SESSION['customer']) && isset($_SESSION['customer']->customerAddresses)) {
	        if (is_array($_SESSION['customer']->customerAddresses) && !empty($_SESSION['customer']->customerAddresses)) {
	            foreach ($_SESSION['customer']->customerAddresses as $customer_address) {
	                if ($customer_address->address->primary) {
	                    return $customer_address->address;
                    }
                }
            }
        }

	    return null;
    }

///*****************************************************************************///

function customer_primary_contact() {
    if (isset($_SESSION['customer']) && isset($_SESSION['customer']->customerContacts)) {
        if (is_array($_SESSION['customer']->customerContacts) && !empty($_SESSION['customer']->customerContacts)) {
            foreach ($_SESSION['customer']->customerContacts as $customer_contact) {
                if ($customer_contact->contact->primary) {
                    return $customer_contact->contact;
                }
            }
        }
    }

    return null;
}

///*****************************************************************************///

function update_customer_address($id, $housenumber = '', $addressline1 = '', $addressline2 = '', $addressline3 = '', $postcode = '', $country = '') {
    if (isset($_SESSION['customer']) && isset($_SESSION['customer']->customerAddresses)) {
        if (is_array($_SESSION['customer']->customerAddresses) && !empty($_SESSION['customer']->customerAddresses)) {
            foreach ($_SESSION['customer']->customerAddresses as $k => $customer_address) {
                if ($customer_address->address->primary) {
                    $_SESSION['customer']->customerAddresses[$k]->address->houseNumber = $housenumber;
                    $_SESSION['customer']->customerAddresses[$k]->address->addressLine1 = $addressline1;
                    $_SESSION['customer']->customerAddresses[$k]->address->addressLine2 = $addressline2;
                    $_SESSION['customer']->customerAddresses[$k]->address->addressLine3 = $addressline3;
                    $_SESSION['customer']->customerAddresses[$k]->address->postcode = $postcode;
                    $_SESSION['customer']->customerAddresses[$k]->address->country = $country;

                    return true;
                }
            }
        }
    }

    return false;
}

///*****************************************************************************///

function update_customer_contact($id, $title = '', $firstname = '', $lastname = '', $phone = '', $marketing = '', $company = '') {
    if (isset($_SESSION['customer']) && isset($_SESSION['customer']->customerContacts)) {
        if (is_array($_SESSION['customer']->customerContacts) && !empty($_SESSION['customer']->customerContacts)) {
            foreach ($_SESSION['customer']->customerContacts as &$customer_contact) {
                if ($customer_contact->contact->id === $id) {
                    $customer_contact->contact->title = $title;
                    $customer_contact->contact->firstName = $firstname;
                    $customer_contact->contact->lastName = $lastname;
                    $customer_contact->contact->phone = $phone;
                    $customer_contact->contact->marketingAllowed = $marketing;
                    $customer_contact->contact->companyName = $company;

                    return true;
                }
            }
        }
    }

    return false;
}

function getCustomerByEmail($email) {
    $requestresult = API::get("customers/email/" . $email);

    return $requestresult['ok'];
}

function price($amount) {
    return number_format($amount, 2);
}

/**
 * Obtain a brand constant from a PAN
 *
 * @param string $pan               Credit card number
 * @param bool   $include_sub_types Include detection of sub visa brands
 * @return string
 */
function getCardBrand($pan, $include_sub_types = false)
{
    //maximum length is not fixed now, there are growing number of CCs has more numbers in length, limiting can give false negatives atm

    //these regexps accept not whole cc numbers too
    //visa
    $visa_regex = "/^4[0-9]{0,}$/";
    $vpreca_regex = "/^428485[0-9]{0,}$/";
    $postepay_regex = "/^(402360|402361|403035|417631|529948){0,}$/";
    $cartasi_regex = "/^(432917|432930|453998)[0-9]{0,}$/";
    $entropay_regex = "/^(406742|410162|431380|459061|533844|522093)[0-9]{0,}$/";
    $o2money_regex = "/^(422793|475743)[0-9]{0,}$/";

    // MasterCard
    $mastercard_regex = "/^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$/";
    $maestro_regex = "/^(5[06789]|6)[0-9]{0,}$/";
    $kukuruza_regex = "/^525477[0-9]{0,}$/";
    $yunacard_regex = "/^541275[0-9]{0,}$/";

    // American Express
    $amex_regex = "/^3[47][0-9]{0,}$/";

    // Diners Club
    $diners_regex = "/^3(?:0[0-59]{1}|[689])[0-9]{0,}$/";

    //Discover
    $discover_regex = "/^(6011|65|64[4-9]|62212[6-9]|6221[3-9]|622[2-8]|6229[01]|62292[0-5])[0-9]{0,}$/";

    //JCB
    $jcb_regex = "/^(?:2131|1800|35)[0-9]{0,}$/";

    //ordering matter in detection, otherwise can give false results in rare cases
    if (preg_match($jcb_regex, $pan)) {
        return "JCB";
    }

    if (preg_match($amex_regex, $pan)) {
        return "AMEX";
    }

    if (preg_match($diners_regex, $pan)) {
        return "DINERSCLUB";
    }

    //sub visa/mastercard cards
    if ($include_sub_types) {
        if (preg_match($vpreca_regex, $pan)) {
            return "V-PRECA";
        }
        if (preg_match($postepay_regex, $pan)) {
            return "POSTEPAY";
        }
        if (preg_match($cartasi_regex, $pan)) {
            return "CARTASI";
        }
        if (preg_match($entropay_regex, $pan)) {
            return "ENTROPAY";
        }
        if (preg_match($o2money_regex, $pan)) {
            return "O2MONEY";
        }
        if (preg_match($kukuruza_regex, $pan)) {
            return "KUKURUZA";
        }
        if (preg_match($yunacard_regex, $pan)) {
            return "YUNACARD";
        }
    }

    if (preg_match($visa_regex, $pan)) {
        return "VISA";
    }

    if (preg_match($mastercard_regex, $pan)) {
        return "MASTERCARD";
    }

    if (preg_match($discover_regex, $pan)) {
        return "DISCOVER";
    }

    if (preg_match($maestro_regex, $pan)) {
        if ($pan[0] == '5') { //started 5 must be mastercard
            return "MASTERCARD";
        }
        return "MAESTRO"; //maestro is all 60-69 which is not something else, thats why this condition in the end

    }

    return "UNKNOWN"; //unknown for this system
}

function number_between($number, $start, $end) {
    if ($number >= $start && $number <= $end)
        return true;

    return false;
}

function getGlobalSettingsFromAPI() {
    $requestresult = API::get('settings');

    if($requestresult['ok'])
    {
        $settings = [];

        if (!empty($requestresult['content']) && is_array($requestresult['content'])) {
            foreach ($requestresult['content'] as $result) {
                $settings[$result->name] = $result->value;
            }
        }

        return $settings;
    }

    return false;
}

function countryList() {
    return [
        "Afghanistan"                                  => "Afghanistan",
        "Åland Islands"                                => "Åland Islands",
        "Albania"                                      => "Albania",
        "Algeria"                                      => "Algeria",
        "American Samoa"                               => "American Samoa",
        "Andorra"                                      => "Andorra",
        "Angola"                                       => "Angola",
        "Anguilla"                                     => "Anguilla",
        "Antarctica"                                   => "Antarctica",
        "Antigua and Barbuda"                          => "Antigua and Barbuda",
        "Argentina"                                    => "Argentina",
        "Armenia"                                      => "Armenia",
        "Aruba"                                        => "Aruba",
        "Australia"                                    => "Australia",
        "Austria"                                      => "Austria",
        "Azerbaijan"                                   => "Azerbaijan",
        "Bahamas"                                      => "Bahamas",
        "Bahrain"                                      => "Bahrain",
        "Bangladesh"                                   => "Bangladesh",
        "Barbados"                                     => "Barbados",
        "Belarus"                                      => "Belarus",
        "Belgium"                                      => "Belgium",
        "Belize"                                       => "Belize",
        "Benin"                                        => "Benin",
        "Bermuda"                                      => "Bermuda",
        "Bhutan"                                       => "Bhutan",
        "Bolivia"                                      => "Bolivia",
        "Bosnia and Herzegovina"                       => "Bosnia and Herzegovina",
        "Botswana"                                     => "Botswana",
        "Bouvet Island"                                => "Bouvet Island",
        "Brazil"                                       => "Brazil",
        "British Indian Ocean Territory"               => "British Indian Ocean Territory",
        "Brunei Darussalam"                            => "Brunei Darussalam",
        "Bulgaria"                                     => "Bulgaria",
        "Burkina Faso"                                 => "Burkina Faso",
        "Burundi"                                      => "Burundi",
        "Cambodia"                                     => "Cambodia",
        "Cameroon"                                     => "Cameroon",
        "Canada"                                       => "Canada",
        "Cape Verde"                                   => "Cape Verde",
        "Cayman Islands"                               => "Cayman Islands",
        "Central African Republic"                     => "Central African Republic",
        "Chad"                                         => "Chad",
        "Chile"                                        => "Chile",
        "China"                                        => "China",
        "Christmas Island"                             => "Christmas Island",
        "Cocos (Keeling) Islands"                      => "Cocos (Keeling) Islands",
        "Colombia"                                     => "Colombia",
        "Comoros"                                      => "Comoros",
        "Congo"                                        => "Congo",
        "Congo, The Democratic Republic of The"        => "Congo, The Democratic Republic of The",
        "Cook Islands"                                 => "Cook Islands",
        "Costa Rica"                                   => "Costa Rica",
        "Cote D'ivoire"                                => "Cote D'ivoire",
        "Croatia"                                      => "Croatia",
        "Cuba"                                         => "Cuba",
        "Cyprus"                                       => "Cyprus",
        "Czech Republic"                               => "Czech Republic",
        "Denmark"                                      => "Denmark",
        "Djibouti"                                     => "Djibouti",
        "Dominica"                                     => "Dominica",
        "Dominican Republic"                           => "Dominican Republic",
        "Ecuador"                                      => "Ecuador",
        "Egypt"                                        => "Egypt",
        "El Salvador"                                  => "El Salvador",
        "Equatorial Guinea"                            => "Equatorial Guinea",
        "Eritrea"                                      => "Eritrea",
        "Estonia"                                      => "Estonia",
        "Ethiopia"                                     => "Ethiopia",
        "Falkland Islands (Malvinas)"                  => "Falkland Islands (Malvinas)",
        "Faroe Islands"                                => "Faroe Islands",
        "Fiji"                                         => "Fiji",
        "Finland"                                      => "Finland",
        "France"                                       => "France",
        "French Guiana"                                => "French Guiana",
        "French Polynesia"                             => "French Polynesia",
        "French Southern Territories"                  => "French Southern Territories",
        "Gabon"                                        => "Gabon",
        "Gambia"                                       => "Gambia",
        "Georgia"                                      => "Georgia",
        "Germany"                                      => "Germany",
        "Ghana"                                        => "Ghana",
        "Gibraltar"                                    => "Gibraltar",
        "Greece"                                       => "Greece",
        "Greenland"                                    => "Greenland",
        "Grenada"                                      => "Grenada",
        "Guadeloupe"                                   => "Guadeloupe",
        "Guam"                                         => "Guam",
        "Guatemala"                                    => "Guatemala",
        "Guernsey"                                     => "Guernsey",
        "Guinea"                                       => "Guinea",
        "Guinea-bissau"                                => "Guinea-bissau",
        "Guyana"                                       => "Guyana",
        "Haiti"                                        => "Haiti",
        "Heard Island and Mcdonald Islands"            => "Heard Island and Mcdonald Islands",
        "Holy See (Vatican City State)"                => "Holy See (Vatican City State)",
        "Honduras"                                     => "Honduras",
        "Hong Kong"                                    => "Hong Kong",
        "Hungary"                                      => "Hungary",
        "Iceland"                                      => "Iceland",
        "India"                                        => "India",
        "Indonesia"                                    => "Indonesia",
        "Iran, Islamic Republic of"                    => "Iran, Islamic Republic of",
        "Iraq"                                         => "Iraq",
        "Ireland"                                      => "Ireland",
        "Isle of Man"                                  => "Isle of Man",
        "Israel"                                       => "Israel",
        "Italy"                                        => "Italy",
        "Jamaica"                                      => "Jamaica",
        "Japan"                                        => "Japan",
        "Jersey"                                       => "Jersey",
        "Jordan"                                       => "Jordan",
        "Kazakhstan"                                   => "Kazakhstan",
        "Kenya"                                        => "Kenya",
        "Kiribati"                                     => "Kiribati",
        "Korea, Democratic People's Republic of"       => "Korea, Democratic People's Republic of",
        "Korea, Republic of"                           => "Korea, Republic of",
        "Kuwait"                                       => "Kuwait",
        "Kyrgyzstan"                                   => "Kyrgyzstan",
        "Lao People's Democratic Republic"             => "Lao People's Democratic Republic",
        "Latvia"                                       => "Latvia",
        "Lebanon"                                      => "Lebanon",
        "Lesotho"                                      => "Lesotho",
        "Liberia"                                      => "Liberia",
        "Libyan Arab Jamahiriya"                       => "Libyan Arab Jamahiriya",
        "Liechtenstein"                                => "Liechtenstein",
        "Lithuania"                                    => "Lithuania",
        "Luxembourg"                                   => "Luxembourg",
        "Macao"                                        => "Macao",
        "Macedonia, The Former Yugoslav Republic of"   => "Macedonia, The Former Yugoslav Republic of",
        "Madagascar"                                   => "Madagascar",
        "Malawi"                                       => "Malawi",
        "Malaysia"                                     => "Malaysia",
        "Maldives"                                     => "Maldives",
        "Mali"                                         => "Mali",
        "Malta"                                        => "Malta",
        "Marshall Islands"                             => "Marshall Islands",
        "Martinique"                                   => "Martinique",
        "Mauritania"                                   => "Mauritania",
        "Mauritius"                                    => "Mauritius",
        "Mayotte"                                      => "Mayotte",
        "Mexico"                                       => "Mexico",
        "Micronesia, Federated States of"              => "Micronesia, Federated States of",
        "Moldova, Republic of"                         => "Moldova, Republic of",
        "Monaco"                                       => "Monaco",
        "Mongolia"                                     => "Mongolia",
        "Montenegro"                                   => "Montenegro",
        "Montserrat"                                   => "Montserrat",
        "Morocco"                                      => "Morocco",
        "Mozambique"                                   => "Mozambique",
        "Myanmar"                                      => "Myanmar",
        "Namibia"                                      => "Namibia",
        "Nauru"                                        => "Nauru",
        "Nepal"                                        => "Nepal",
        "Netherlands"                                  => "Netherlands",
        "Netherlands Antilles"                         => "Netherlands Antilles",
        "New Caledonia"                                => "New Caledonia",
        "New Zealand"                                  => "New Zealand",
        "Nicaragua"                                    => "Nicaragua",
        "Niger"                                        => "Niger",
        "Nigeria"                                      => "Nigeria",
        "Niue"                                         => "Niue",
        "Norfolk Island"                               => "Norfolk Island",
        "Northern Mariana Islands"                     => "Northern Mariana Islands",
        "Norway"                                       => "Norway",
        "Oman"                                         => "Oman",
        "Pakistan"                                     => "Pakistan",
        "Palau"                                        => "Palau",
        "Palestinian Territory, Occupied"              => "Palestinian Territory, Occupied",
        "Panama"                                       => "Panama",
        "Papua New Guinea"                             => "Papua New Guinea",
        "Paraguay"                                     => "Paraguay",
        "Peru"                                         => "Peru",
        "Philippines"                                  => "Philippines",
        "Pitcairn"                                     => "Pitcairn",
        "Poland"                                       => "Poland",
        "Portugal"                                     => "Portugal",
        "Puerto Rico"                                  => "Puerto Rico",
        "Qatar"                                        => "Qatar",
        "Reunion"                                      => "Reunion",
        "Romania"                                      => "Romania",
        "Russian Federation"                           => "Russian Federation",
        "Rwanda"                                       => "Rwanda",
        "Saint Helena"                                 => "Saint Helena",
        "Saint Kitts and Nevis"                        => "Saint Kitts and Nevis",
        "Saint Lucia"                                  => "Saint Lucia",
        "Saint Pierre and Miquelon"                    => "Saint Pierre and Miquelon",
        "Saint Vincent and The Grenadines"             => "Saint Vincent and The Grenadines",
        "Samoa"                                        => "Samoa",
        "San Marino"                                   => "San Marino",
        "Sao Tome and Principe"                        => "Sao Tome and Principe",
        "Saudi Arabia"                                 => "Saudi Arabia",
        "Senegal"                                      => "Senegal",
        "Serbia"                                       => "Serbia",
        "Seychelles"                                   => "Seychelles",
        "Sierra Leone"                                 => "Sierra Leone",
        "Singapore"                                    => "Singapore",
        "Slovakia"                                     => "Slovakia",
        "Slovenia"                                     => "Slovenia",
        "Solomon Islands"                              => "Solomon Islands",
        "Somalia"                                      => "Somalia",
        "South Africa"                                 => "South Africa",
        "South Georgia and The South Sandwich Islands" => "South Georgia and The South Sandwich Islands",
        "Spain"                                        => "Spain",
        "Sri Lanka"                                    => "Sri Lanka",
        "Sudan"                                        => "Sudan",
        "Suriname"                                     => "Suriname",
        "Svalbard and Jan Mayen"                       => "Svalbard and Jan Mayen",
        "Swaziland"                                    => "Swaziland",
        "Sweden"                                       => "Sweden",
        "Switzerland"                                  => "Switzerland",
        "Syrian Arab Republic"                         => "Syrian Arab Republic",
        "Taiwan, Province of China"                    => "Taiwan, Province of China",
        "Tajikistan"                                   => "Tajikistan",
        "Tanzania, United Republic of"                 => "Tanzania, United Republic of",
        "Thailand"                                     => "Thailand",
        "Timor-leste"                                  => "Timor-leste",
        "Togo"                                         => "Togo",
        "Tokelau"                                      => "Tokelau",
        "Tonga"                                        => "Tonga",
        "Trinidad and Tobago"                          => "Trinidad and Tobago",
        "Tunisia"                                      => "Tunisia",
        "Turkey"                                       => "Turkey",
        "Turkmenistan"                                 => "Turkmenistan",
        "Turks and Caicos Islands"                     => "Turks and Caicos Islands",
        "Tuvalu"                                       => "Tuvalu",
        "Uganda"                                       => "Uganda",
        "Ukraine"                                      => "Ukraine",
        "United Arab Emirates"                         => "United Arab Emirates",
        "United Kingdom"                               => "United Kingdom",
        "United States"                                => "United States",
        "United States Minor Outlying Islands"         => "United States Minor Outlying Islands",
        "Uruguay"                                      => "Uruguay",
        "Uzbekistan"                                   => "Uzbekistan",
        "Vanuatu"                                      => "Vanuatu",
        "Venezuela"                                    => "Venezuela",
        "Viet Nam"                                     => "Viet Nam",
        "Virgin Islands, British"                      => "Virgin Islands, British",
        "Virgin Islands, U.S."                         => "Virgin Islands, U.S.",
        "Wallis and Futuna"                            => "Wallis and Futuna",
        "Western Sahara"                               => "Western Sahara",
        "Yemen"                                        => "Yemen",
        "Zambia"                                       => "Zambia",
        "Zimbabwe"                                     => "Zimbabwe",
    ];
}

function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

function leapYearCount($start_year, $end_year) {
    $count = 0;

    foreach (range($start_year, $end_year) as $year) {
        if ((bool) date('L', strtotime("$year-01-01")))
            $count++;
    }

    return $count;
}

function getGlobalSettings() {
    $globalSettingsUpdatedAt = Cache::get('settingsUpdatedAt');

    if (!$globalSettingsUpdatedAt || $globalSettingsUpdatedAt + 5 * 60 < time()) {
        $globalSettings = getGlobalSettingsFromAPI();
        Cache::set('globalSettings', $globalSettings);
        Cache::set('settingsUpdatedAt', time());
    } else if (!$globalSettings = Cache::get('globalSettings')) {
        Cache::delete('settingsUpdatedAt');

        return getGlobalSettings();
    }

    return $globalSettings;
}

function trailingslashit($string) {
    return untrailingslashit($string) . '/';
}

function untrailingslashit($string) {
    return rtrim($string, '/\\');
}