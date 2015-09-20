<?php

class Common {

    function trim_text($string, $word_count=100) {
       $trimmed = "";
       $string = preg_replace("/\040+/"," ", trim($string));
       $stringc = explode(" ",$string);
       
       if($word_count >= sizeof($stringc)) {
           // nothing to do, our string is smaller than the limit.
         return $string;
       } else if($word_count < sizeof($stringc)) {
           // trim the string to the word count
           for($i=0;$i<$word_count;$i++) {
               $trimmed .= $stringc[$i]." ";
           }

           if(substr($trimmed, strlen(trim($trimmed))-1, 1) == '.')
             return trim($trimmed).'..';
           else
             return trim($trimmed).'...';
       }
    }
    function get_alpha() {
        $chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S' ,'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $new = array();
        $new['all'] = 'All';
        foreach($chars as $value)
            $new[$value] = $value;
        return $new;
    }
    function get_textual($num) {
        $textual = array(
            1=>'First',
            2=>'Second',
            3=>'Third',
            4=>'Fourth',
            5=>'Fifth',
            6=>'Sixth',
            7=>'Seventh',
            8=>'Eighth',
            9=>'Ninth',
            10=>'Tenth');

       return $textual[$num];
    }
    
    function get_num_textual($num) {
        $textual = array(
            1=>'one',
            2=>'two',
            3=>'three',
            4=>'four',
            5=>'five',
            6=>'six',
            7=>'seven',
            8=>'eigth',
            9=>'nine',
            10=>'ten');

       return $textual[$num];
    }
    
    function htmldecode($mystr) {
        $entities = get_html_translation_table (HTML_ENTITIES);
        $specialchars = get_html_translation_table (HTML_SPECIALCHARS);

        foreach($entities as $key => $value){
           if (!in_array($value, $specialchars)) {
                $mystr = str_replace($key, $value, $mystr);
           }
       }
       return $mystr;
    }

    function array_sort($array, $key) {
       foreach ($array as $i => $k) {
        $sort_values[$i] = $array[$i][$key];
       }
       asort ($sort_values);
       reset ($sort_values);
       while (list ($arr_key, $arr_val) = each ($sort_values)) {
             $sorted_arr[] = $array[$arr_key];
       }
       return $sorted_arr;
    }


     function tidy_it($stripmeta, $content) {
        if (function_exists(tidy_repair_string)) {
            tidy_set_encoding('utf8');
            //$tidy = tidy_repair_string(stripslashes($content));
            if ($stripmeta==TRUE) {
                $config = array('show-body-only' => TRUE);
            } else {
                $config = array('show-body-only' => FALSE, 'output-xml'=>FALSE);
            }
            $tidy = tidy_repair_string(stripslashes($content), $config, 'utf8');
        } else {
            $tidy = stripslashes($content);
        }
        return $tidy;
    }
    function get_double_months($sel=FALSE) {
        $months = array(
            '00'=>"N/A",
            '01'=>"January",
            '02'=>"February",
            '03'=>"March",
            '04'=>"April",
            '05'=>"May",
            '06'=>"June",
            '07'=>"July",
            '08'=>"August",
            '09'=>"September",
            '10'=>"October",
            '11'=>"November",
            '12'=>"December");
        if ($sel!=FALSE)
            return $months[$sel];
        else
    	   return $months;
    }
    
    function get_months($sel=FALSE) {
        $months = array(
            0=>"N/A",
            1=>"January",
            2=>"February",
            3=>"March",
            4=>"April",
            5=>"May",
            6=>"June",
            7=>"July",
            8=>"August",
            9=>"September",
            10=>"October",
            11=>"November",
            12=>"December");
        if ($sel!=FALSE)
            return $months[$sel];
        else
    	   return $months;
    }
    
    function checkEmail($email)  {
        return (preg_match("/^[\w|\d]+(([\-|\.][\w|\d]+)+)?@([\w|\d|\-]+\.)+[\w]{2,4}$/gsi", $email));
    }

    function checkEmail1($email)  {
        return (eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $email)) ? TRUE : FALSE;
    }

    function get_prefixes() {
        return $prefixes = array(""=>"", "Mr."=>"Mr.", "Mrs."=>"Mrs.", "Ms."=>"Ms.", "Dr."=>"Dr.");
    }

    function get_states() {
        $states = array(''=>' Select ',
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming');

         return $states;
    }
    
    function get_countries_search() {
        $db = new Db();
        $arr[''] = 'Any';
        $db->Query("SELECT id, country_name FROM country WHERE is_active=1 ORDER BY country_name");
        while($data = $db->fetchAssoc()) {
        	$arr[$data['id']] = $data['country_name'];
        }
        return $arr;
    }

    function get_countries() {
        $array[''] = 'Select Country';
        $countries = Common::get_countries_list();
        return array_merge($array, $countries);
    }
    
    function get_linked_countries($arr) {
        $list = implode(',', $arr);
        $db = new Db();
        $db->Query("SELECT id FROM country WHERE show_link=1 AND FIND_IN_SET(id, '".$list."')");
        while($data = $db->fetchAssoc()) {
        	$ret[] = $data['id'];
        }
        return $ret;
    }

    function get_countries_list() {
        return array(
        'United States'=>'United States',
        'Afghanistan'=>'Afghanistan',
        'Albania'=>'Albania',
        'Algeria'=>'Algeria',
        'American Samoa'=>'American Samoa',
        'Andorra'=>'Andorra',
        'Angola'=>'Angola',
        'Anguilla'=>'Anguilla',
        'Antarctica'=>'Antarctica',
        'Antigua And Barbuda'=>'Antigua And Barbuda',
        'Argentina'=>'Argentina',
        'Armenia'=>'Armenia',
        'Aruba'=>'Aruba',
        'Australia'=>'Australia',
        'Austria'=>'Austria',
        'Azerbaijan'=>'Azerbaijan',
        'Bahamas'=>'Bahamas',
        'Bahrain'=>'Bahrain',
        'Bangladesh'=>'Bangladesh',
        'Barbados'=>'Barbados',
        'Belarus'=>'Belarus',
        'Belgium'=>'Belgium',
        'Belize'=>'Belize',
        'Benin'=>'Benin',
        'Bermuda'=>'Bermuda',
        'Bhutan'=>'Bhutan',
        'Bolivia'=>'Bolivia',
        'Bosnia And Herzegovina'=>'Bosnia And Herzegovina',
        'Botswana'=>'Botswana',
        'Bouvet Island'=>'Bouvet Island',
        'Brazil'=>'Brazil',
        'British Indian Ocean Territory'=>'British Indian Ocean Territory',
        'Brunei Darussalam'=>'Brunei Darussalam',
        'Bulgaria'=>'Bulgaria',
        'Burkina Faso'=>'Burkina Faso',
        'Burundi'=>'Burundi',
        'Cambodia'=>'Cambodia',
        'Cameroon'=>'Cameroon',
        'Canada'=>'Canada',
        'Cape Verde'=>'Cape Verde',
        'Cayman Islands'=>'Cayman Islands',
        'Central African Republic'=>'Central African Republic',
        'Chad'=>'Chad',
        'Chile'=>'Chile',
        'China'=>'China',
        'Christmas Island'=>'Christmas Island',
        'Cocos (Keeling) Islands'=>'Cocos (Keeling) Islands',
        'Colombia'=>'Colombia',
        'Comoros'=>'Comoros',
        'Congo'=>'Congo',
        'Congo, The Democratic Republic Of '=>'Congo, The Democratic Republic Of ',
        'Cook Islands'=>'Cook Islands',
        'Costa Rica'=>'Costa Rica',
        'Cote D Ivoire'=>'Cote D Ivoire',
        'Croatia'=>'Croatia',
        'Cuba'=>'Cuba',
        'Cyprus'=>'Cyprus',
        'Czech Republic'=>'Czech Republic',
        'Denmark'=>'Denmark',
        'Djibouti'=>'Djibouti',
        'Dominica'=>'Dominica',
        'Dominican Republic'=>'Dominican Republic',
        'East Timor'=>'East Timor',
        'Ecuador'=>'Ecuador',
        'Egypt'=>'Egypt',
        'El Salvador'=>'El Salvador',
        'Equatorial Guinea'=>'Equatorial Guinea',
        'Eritrea'=>'Eritrea',
        'Estonia'=>'Estonia',
        'Ethiopia'=>'Ethiopia',
        'Falkland Islands (malvinas)'=>'Falkland Islands (malvinas)',
        'Faroe Islands'=>'Faroe Islands',
        'Fiji'=>'Fiji',
        'Finland'=>'Finland',
        'France'=>'France',
        'French Guiana'=>'French Guiana',
        'French Polynesia'=>'French Polynesia',
        'French Southern Territories'=>'French Southern Territories',
        'Gabon'=>'Gabon',
        'Gambia'=>'Gambia',
        'Georgia'=>'Georgia',
        'Germany'=>'Germany',
        'Ghana'=>'Ghana',
        'Gibraltar'=>'Gibraltar',
        'Greece'=>'Greece',
        'Greenland'=>'Greenland',
        'Grenada'=>'Grenada',
        'Guadeloupe'=>'Guadeloupe',
        'Guam'=>'Guam',
        'Guatemala'=>'Guatemala',
        'Guinea'=>'Guinea',
        'Guinea-bissau'=>'Guinea-bissau',
        'Guyana'=>'Guyana',
        'Haiti'=>'Haiti',
        'Heard Island And Mcdonald Islands'=>'Heard Island And Mcdonald Islands',
        'Holy See (Vatican City State)'=>'Holy See (Vatican City State)',
        'Honduras'=>'Honduras',
        'Hong Kong'=>'Hong Kong',
        'Hungary'=>'Hungary',
        'Iceland'=>'Iceland',
        'India'=>'India',
        'Indonesia'=>'Indonesia',
        'Iran, Islamic Republic Of'=>'Iran, Islamic Republic Of',
        'Iraq'=>'Iraq',
        'Ireland'=>'Ireland',
        'Israel'=>'Israel',
        'Italy'=>'Italy',
        'Jamaica'=>'Jamaica',
        'Japan'=>'Japan',
        'Jordan'=>'Jordan',
        'Kazakstan'=>'Kazakstan',
        'Kenya'=>'Kenya',
        'Kiribati'=>'Kiribati',
        'Korea Democratic Peoples Republic Of'=>'Korea Democratic Peoples Republic Of',
        'Korea Republic Of'=>'Korea Republic Of',
        'Kuwait'=>'Kuwait',
        'Kyrgyzstan'=>'Kyrgyzstan',
        'Lao Peoples Democratic Republic'=>'Lao Peoples Democratic Republic',
        'Latvia'=>'Latvia',
        'Lebanon'=>'Lebanon',
        'Lesotho'=>'Lesotho',
        'Liberia'=>'Liberia',
        'Libyan Arab Jamahiriya'=>'Libyan Arab Jamahiriya',
        'Liechtenstein'=>'Liechtenstein',
        'Lithuania'=>'Lithuania',
        'Luxembourg'=>'Luxembourg',
        'Macau'=>'Macau',
        'Macedonia, The Former Yugoslav Republic Of'=>'Macedonia, The Former Yugoslav Republic Of',
        'Madagascar'=>'Madagascar',
        'Malawi'=>'Malawi',
        'Malaysia'=>'Malaysia',
        'Maldives'=>'Maldives',
        'Mali'=>'Mali',
        'Malta'=>'Malta',
        'Marshall Islands'=>'Marshall Islands',
        'Martinique'=>'Martinique',
        'Mauritania'=>'Mauritania',
        'Mauritius'=>'Mauritius',
        'Mayotte'=>'Mayotte',
        'Mexico'=>'Mexico',
        'Micronesia, Federated States Of'=>'Micronesia, Federated States Of',
        'Moldova, Republic Of'=>'Moldova, Republic Of',
        'Monaco'=>'Monaco',
        'Mongolia'=>'Mongolia',
        'Montserrat'=>'Montserrat',
        'Morocco'=>'Morocco',
        'Mozambique'=>'Mozambique',
        'Myanmar'=>'Myanmar',
        'Namibia'=>'Namibia',
        'Nauru'=>'Nauru',
        'Nepal'=>'Nepal',
        'Netherlands'=>'Netherlands',
        'Netherlands Antilles'=>'Netherlands Antilles',
        'New Caledonia'=>'New Caledonia',
        'New Zealand'=>'New Zealand',
        'Nicaragua'=>'Nicaragua',
        'Niger'=>'Niger',
        'Nigeria'=>'Nigeria',
        'Niue'=>'Niue',
        'Norfolk Island'=>'Norfolk Island',
        'Northern Mariana Islands'=>'Northern Mariana Islands',
        'Norway'=>'Norway',
        'Oman'=>'Oman',
        'Pakistan'=>'Pakistan',
        'Palau'=>'Palau',
        'Palestinian Territory, Occupied'=>'Palestinian Territory, Occupied',
        'Panama'=>'Panama',
        'Papua New Guinea'=>'Papua New Guinea',
        'Paraguay'=>'Paraguay',
        'Peru'=>'Peru',
        'Philippines'=>'Philippines',
        'Pitcairn'=>'Pitcairn',
        'Poland'=>'Poland',
        'Portugal'=>'Portugal',
        'Puerto Rico'=>'Puerto Rico',
        'Qatar'=>'Qatar',
        'Reunion'=>'Reunion',
        'Romania'=>'Romania',
        'Russian Federation'=>'Russian Federation',
        'Rwanda'=>'Rwanda',
        'Saint Helena'=>'Saint Helena',
        'Saint Kitts And Nevis'=>'Saint Kitts And Nevis',
        'Saint Lucia'=>'Saint Lucia',
        'Saint Pierre And Miquelon'=>'Saint Pierre And Miquelon',
        'Saint Vincent And The Grenadines'=>'Saint Vincent And The Grenadines',
        'Samoa'=>'Samoa',
        'San Marino'=>'San Marino',
        'Sao Tome And Principe'=>'Sao Tome And Principe',
        'Saudi Arabia'=>'Saudi Arabia',
        'Senegal'=>'Senegal',
        'Seychelles'=>'Seychelles',
        'Sierra Leone'=>'Sierra Leone',
        'Singapore'=>'Singapore',
        'Slovakia'=>'Slovakia',
        'Slovenia'=>'Slovenia',
        'Solomon Islands'=>'Solomon Islands',
        'Somalia'=>'Somalia',
        'South Africa'=>'South Africa',
        'South Georgia And The South Sandwich Islands'=>'South Georgia And The South Sandwich Islands',
        'Spain'=>'Spain',
        'Sri Lanka'=>'Sri Lanka',
        'Sudan'=>'Sudan',
        'Suriname'=>'Suriname',
        'Svalbard And Jan Mayen'=>'Svalbard And Jan Mayen',
        'Swaziland'=>'Swaziland',
        'Sweden'=>'Sweden',
        'Switzerland'=>'Switzerland',
        'Syrian Arab Republic'=>'Syrian Arab Republic',
        'Taiwan, Province Of China'=>'Taiwan, Province Of China',
        'Tajikistan'=>'Tajikistan',
        'Tanzania, United Republic Of'=>'Tanzania, United Republic Of',
        'Thailand'=>'Thailand',
        'Togo'=>'Togo',
        'Tokelau'=>'Tokelau',
        'Tonga'=>'Tonga',
        'Trinidad And Tobago'=>'Trinidad And Tobago',
        'Tunisia'=>'Tunisia',
        'Turkey'=>'Turkey',
        'Turkmenistan'=>'Turkmenistan',
        'Turks And Caicos Islands'=>'Turks And Caicos Islands',
        'Tuvalu'=>'Tuvalu',
        'Uganda'=>'Uganda',
        'Ukraine'=>'Ukraine',
        'United Arab Emirates'=>'United Arab Emirates',
        'United Kingdom'=>'United Kingdom',
        'United States Minor Outlying Islands'=>'United States Minor Outlying Islands',
        'Uruguay'=>'Uruguay',
        'Uzbekistan'=>'Uzbekistan',
        'Vanuatu'=>'Vanuatu',
        'Venezuela'=>'Venezuela',
        'Viet Nam'=>'Viet Nam',
        'Virgin Islands, British'=>'Virgin Islands, British',
        'Virgin Islands, U.s.'=>'Virgin Islands, U.s.',
        'Wallis And Futuna'=>'Wallis And Futuna',
        'Western Sahara'=>'Western Sahara',
        'Yemen'=>'Yemen',
        'Yugoslavia'=>'Yugoslavia',
        'Zambia'=>'Zambia',
        'Zimbabwe'=>'Zimbabwe');
    }
    
    function get_country_codes() {
        $country_array = array(
        'AF'=>'AFGHANISTAN',
        'AL'=>'ALBANIA',
        'DZ'=>'ALGERIA',
        'AS'=>'AMERICAN SAMOA',
        'AD'=>'ANDORRA',
        'AO'=>'ANGOLA',
        'AI'=>'ANGUILLA',
        'AQ'=>'ANTARCTICA',
        'AG'=>'ANTIGUA AND BARBUDA',
        'AR'=>'ARGENTINA',
        'AM'=>'ARMENIA',
        'AW'=>'ARUBA',
        'AU'=>'AUSTRALIA',
        'AT'=>'AUSTRIA',
        'AZ'=>'AZERBAIJAN',
        'BS'=>'BAHAMAS',
        'BH'=>'BAHRAIN',
        'BD'=>'BANGLADESH',
        'BB'=>'BARBADOS',
        'BY'=>'BELARUS',
        'BE'=>'BELGIUM',
        'BZ'=>'BELIZE',
        'BJ'=>'BENIN',
        'BM'=>'BERMUDA',
        'BT'=>'BHUTAN',
        'BO'=>'BOLIVIA',
        'BA'=>'BOSNIA AND HERZEGOVINA',
        'BW'=>'BOTSWANA',
        'BV'=>'BOUVET ISLAND',
        'BR'=>'BRAZIL',
        'IO'=>'BRITISH INDIAN OCEAN TERRITORY',
        'BN'=>'BRUNEI DARUSSALAM',
        'BG'=>'BULGARIA',
        'BF'=>'BURKINA FASO',
        'BI'=>'BURUNDI',
        'KH'=>'CAMBODIA',
        'CM'=>'CAMEROON',
        'CA'=>'CANADA',
        'CV'=>'CAPE VERDE',
        'KY'=>'CAYMAN ISLANDS',
        'CF'=>'CENTRAL AFRICAN REPUBLIC',
        'TD'=>'CHAD',
        'CL'=>'CHILE',
        'CN'=>'CHINA',
        'CX'=>'CHRISTMAS ISLAND',
        'CC'=>'COCOS (KEELING) ISLANDS',
        'CO'=>'COLOMBIA',
        'KM'=>'COMOROS',
        'CG'=>'CONGO',
        'CD'=>'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
        'CK'=>'COOK ISLANDS',
        'CR'=>'COSTA RICA',
        'CI'=>'COTE D IVOIRE',
        'HR'=>'CROATIA',
        'CU'=>'CUBA',
        'CY'=>'CYPRUS',
        'CZ'=>'CZECH REPUBLIC',
        'DK'=>'DENMARK',
        'DJ'=>'DJIBOUTI',
        'DM'=>'DOMINICA',
        'DO'=>'DOMINICAN REPUBLIC',
        'TP'=>'EAST TIMOR',
        'EC'=>'ECUADOR',
        'EG'=>'EGYPT',
        'SV'=>'EL SALVADOR',
        'GQ'=>'EQUATORIAL GUINEA',
        'ER'=>'ERITREA',
        'EE'=>'ESTONIA',
        'ET'=>'ETHIOPIA',
        'FK'=>'FALKLAND ISLANDS (MALVINAS)',
        'FO'=>'FAROE ISLANDS',
        'FJ'=>'FIJI',
        'FI'=>'FINLAND',
        'FR'=>'FRANCE',
        'GF'=>'FRENCH GUIANA',
        'PF'=>'FRENCH POLYNESIA',
        'TF'=>'FRENCH SOUTHERN TERRITORIES',
        'GA'=>'GABON',
        'GM'=>'GAMBIA',
        'GE'=>'GEORGIA',
        'DE'=>'GERMANY',
        'GH'=>'GHANA',
        'GI'=>'GIBRALTAR',
        'GR'=>'GREECE',
        'GL'=>'GREENLAND',
        'GD'=>'GRENADA',
        'GP'=>'GUADELOUPE',
        'GU'=>'GUAM',
        'GT'=>'GUATEMALA',
        'GN'=>'GUINEA',
        'GW'=>'GUINEA-BISSAU',
        'GY'=>'GUYANA',
        'HT'=>'HAITI',
        'HM'=>'HEARD ISLAND AND MCDONALD ISLANDS',
        'VA'=>'HOLY SEE (VATICAN CITY STATE)',
        'HN'=>'HONDURAS',
        'HK'=>'HONG KONG',
        'HU'=>'HUNGARY',
        'IS'=>'ICELAND',
        'IN'=>'INDIA',
        'ID'=>'INDONESIA',
        'IR'=>'IRAN, ISLAMIC REPUBLIC OF',
        'IQ'=>'IRAQ',
        'IE'=>'IRELAND',
        'IL'=>'ISRAEL',
        'IT'=>'ITALY',
        'JM'=>'JAMAICA',
        'JP'=>'JAPAN',
        'JO'=>'JORDAN',
        'KZ'=>'KAZAKSTAN',
        'KE'=>'KENYA',
        'KI'=>'KIRIBATI',
        'KP'=>'KOREA, DEMOCRATIC PEOPLES REPUBLIC OF',
        'KR'=>'KOREA, REPUBLIC OF',
        'KW'=>'KUWAIT',
        'KG'=>'KYRGYZSTAN',
        'LA'=>'LAO PEOPLES DEMOCRATIC REPUBLIC',
        'LV'=>'LATVIA',
        'LB'=>'LEBANON',
        'LS'=>'LESOTHO',
        'LR'=>'LIBERIA',
        'LY'=>'LIBYAN ARAB JAMAHIRIYA',
        'LI'=>'LIECHTENSTEIN',
        'LT'=>'LITHUANIA',
        'LU'=>'LUXEMBOURG',
        'MO'=>'MACAU',
        'MK'=>'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
        'MG'=>'MADAGASCAR',
        'MW'=>'MALAWI',
        'MY'=>'MALAYSIA',
        'MV'=>'MALDIVES',
        'ML'=>'MALI',
        'MT'=>'MALTA',
        'MH'=>'MARSHALL ISLANDS',
        'MQ'=>'MARTINIQUE',
        'MR'=>'MAURITANIA',
        'MU'=>'MAURITIUS',
        'YT'=>'MAYOTTE',
        'MX'=>'MEXICO',
        'FM'=>'MICRONESIA, FEDERATED STATES OF',
        'MD'=>'MOLDOVA, REPUBLIC OF',
        'MC'=>'MONACO',
        'MN'=>'MONGOLIA',
        'MS'=>'MONTSERRAT',
        'MA'=>'MOROCCO',
        'MZ'=>'MOZAMBIQUE',
        'MM'=>'MYANMAR',
        'NA'=>'NAMIBIA',
        'NR'=>'NAURU',
        'NP'=>'NEPAL',
        'NL'=>'NETHERLANDS',
        'AN'=>'NETHERLANDS ANTILLES',
        'NC'=>'NEW CALEDONIA',
        'NZ'=>'NEW ZEALAND',
        'NI'=>'NICARAGUA',
        'NE'=>'NIGER',
        'NG'=>'NIGERIA',
        'NU'=>'NIUE',
        'NF'=>'NORFOLK ISLAND',
        'MP'=>'NORTHERN MARIANA ISLANDS',
        'NO'=>'NORWAY',
        'OM'=>'OMAN',
        'PK'=>'PAKISTAN',
        'PW'=>'PALAU',
        'PS'=>'PALESTINIAN TERRITORY, OCCUPIED',
        'PA'=>'PANAMA',
        'PG'=>'PAPUA NEW GUINEA',
        'PY'=>'PARAGUAY',
        'PE'=>'PERU',
        'PH'=>'PHILIPPINES',
        'PN'=>'PITCAIRN',
        'PL'=>'POLAND',
        'PT'=>'PORTUGAL',
        'PR'=>'PUERTO RICO',
        'QA'=>'QATAR',
        'RE'=>'REUNION',
        'RO'=>'ROMANIA',
        'RU'=>'RUSSIAN FEDERATION',
        'RW'=>'RWANDA',
        'SH'=>'SAINT HELENA',
        'KN'=>'SAINT KITTS AND NEVIS',
        'LC'=>'SAINT LUCIA',
        'PM'=>'SAINT PIERRE AND MIQUELON',
        'VC'=>'SAINT VINCENT AND THE GRENADINES',
        'WS'=>'SAMOA',
        'SM'=>'SAN MARINO',
        'ST'=>'SAO TOME AND PRINCIPE',
        'SA'=>'SAUDI ARABIA',
        'SN'=>'SENEGAL',
        'SC'=>'SEYCHELLES',
        'SL'=>'SIERRA LEONE',
        'SG'=>'SINGAPORE',
        'SK'=>'SLOVAKIA',
        'SI'=>'SLOVENIA',
        'SB'=>'SOLOMON ISLANDS',
        'SO'=>'SOMALIA',
        'ZA'=>'SOUTH AFRICA',
        'GS'=>'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
        'ES'=>'SPAIN',
        'LK'=>'SRI LANKA',
        'SD'=>'SUDAN',
        'SR'=>'SURINAME',
        'SJ'=>'SVALBARD AND JAN MAYEN',
        'SZ'=>'SWAZILAND',
        'SE'=>'SWEDEN',
        'CH'=>'SWITZERLAND',
        'SY'=>'SYRIAN ARAB REPUBLIC',
        'TW'=>'TAIWAN, PROVINCE OF CHINA',
        'TJ'=>'TAJIKISTAN',
        'TZ'=>'TANZANIA, UNITED REPUBLIC OF',
        'TH'=>'THAILAND',
        'TG'=>'TOGO',
        'TK'=>'TOKELAU',
        'TO'=>'TONGA',
        'TT'=>'TRINIDAD AND TOBAGO',
        'TN'=>'TUNISIA',
        'TR'=>'TURKEY',
        'TM'=>'TURKMENISTAN',
        'TC'=>'TURKS AND CAICOS ISLANDS',
        'TV'=>'TUVALU',
        'UG'=>'UGANDA',
        'UA'=>'UKRAINE',
        'AE'=>'UNITED ARAB EMIRATES',
        'GB'=>'UNITED KINGDOM',
        'US'=>'UNITED STATES',
        'UM'=>'UNITED STATES MINOR OUTLYING ISLANDS',
        'UY'=>'URUGUAY',
        'UZ'=>'UZBEKISTAN',
        'VU'=>'VANUATU',
        'VE'=>'VENEZUELA',
        'VN'=>'VIET NAM',
        'VG'=>'VIRGIN ISLANDS, BRITISH',
        'VI'=>'VIRGIN ISLANDS, U.S.',
        'WF'=>'WALLIS AND FUTUNA',
        'EH'=>'WESTERN SAHARA',
        'YE'=>'YEMEN',
        'YU'=>'YUGOSLAVIA',
        'ZM'=>'ZAMBIA',
        'ZW'=>'ZIMBABWE');

        foreach($country_array as $key=> $value) {
            $newone[$key] = ucwords(strtolower($value));
        }
        return $newone;
    }

	function get_party_types() {
    	//include('cfg_party_types.php');
    	//updated by Nate 8/12/2008
		$db = new Db();
        $arr = array();
        $db->Query("SELECT id, party_type FROM party_types ORDER BY party_type ASC");
        while($data = $db->fetchAssoc()) {
        	$arr[$data['id']] = $data['party_type'];
        }
		
		return $arr;
	}
	
	function get_party_type($type) {
    	include('cfg_party_types.php');
    	return ($party_types[$type]!='') ? $party_types[$type]:'Party';
	}
	
	function get_link_types() {
    	//include('cfg_link_types.php');
		
		//updated by Nate 8/12/2008
		$db = new Db();
        $arr = array();
        $db->Query("SELECT id, link_type FROM link_types ORDER BY link_type ASC");
        while($data = $db->fetchAssoc()) {
        	$arr[$data['id']] = $data['link_type'];
        }
    	return $arr;
	}

	function get_round_options() {
	    $rounds[0] = 'N/A';   
    	include('cfg_rounds.php');
    	foreach($round_texts as $id=>$value)
    	   $rounds[$id] = $value;
        return $rounds;
	}

	function get_round_options1() {
	    $rounds[0] = '';
    	include('cfg_rounds.php');
    	foreach($round_texts as $id=>$value)
    	   $rounds[$id] = $value;
        return $rounds;
	}
	
	function get_status_options() {
    	//include('cfg_status.php');
        
		//updated by Nate 8/12/2008
		$db = new Db();
        $arr = array();
        $db->Query("SELECT id, status_text FROM status_texts ORDER BY status_text ASC");
        while($data = $db->fetchAssoc()) {
        	$arr[$data['id']] = $data['status_text'];
        }
    	return $arr;
	}
	
    function get_regions($addempty=TRUE, $str='None Selected') {
        $regions = Common::get_region_list();
        if ($addempty==TRUE) {
            $array = array('' => $str);
            return array_merge($array, $regions);
        } else {
            return $regions;
        } 
    }
    
    function get_region_name($id) {
        $regions = Common::get_region_list();
        return $regions[$id]; 
    }
    	
    function get_regions_for_order() {
        return array(
            ''=>'Select Region',
            1=>'Africa',
            2=>'Middle East',
            3=>'Americas',
            4=>'Europe',
            5=>'Asia & Pacific',);
    }
    
    function get_region_list() {
        return array(
            '1'=>'Africa',
            '2'=>'Middle East',
            '3'=>'Americas',
            '4'=>'Europe',
            '5'=>'Asia & Pacific',);
    }

    function get_region_selected($id) {
        $locations = Common::get_region_list();
        return $locations[$id];
    }
    
	function get_yesno() {
		return array(1=>' Yes ', 2=>' No ', 3=>' N/A ');
	}

	function get_election_types($addempty=FALSE) {
        if ($addempty==TRUE) {
    	$array = array(
            ''=>'Any',
    		1=>'Presidential',
            2=>'Parliamentary',
            3=>'Legislative',
            4=>'Referendum');
        } else {
    	$array = array(
    		1=>'Presidential',
            2=>'Parliamentary',
            3=>'Legislative',
            4=>'Referendum');
        }
    	return $array;
    }
    
	function get_election_type($chosen) {
    	$array = array(
    		1=>'Presidential',
            2=>'Parliamentary',
            3=>'Legislative',
            4=>'Referendum');

    	return $array[$chosen];
    }
    
	function get_election_byways() {
        include('cfg_ways.php');
        return $election_ways;
    }

	function get_election_byway($sel='') {
        $thearray = Common::get_election_byways();
    	return $thearray[$sel];
    }
    
    
	function get_election_type_ways() {
        include('cfg_assembly_ways.php');
        asort($assembly_ways);
        return $assembly_ways;
    }

	function get_election_type_way($sel='') {
        $thearray = Common::get_election_type_ways();
        return $thearray[$sel];
    }
    
    
	function get_chief_elected() {
        include('cfg_assembly_ways.php');
        return $assembly_ways;
    }
    
	function get_chief_elected_way($sel='') {
        $thearray = Common::get_chief_elected();
        return $thearray[$sel];
    }
    
	function get_snippets() {
	   $arr[''] = 'Select';
    	$db = new Db();
    	$db->Query("SELECT id, snippet_name FROM snippets ORDER BY snippet_name");
    	while($data = $db->fetchAssoc())
    	   $arr[$data['id']] = $data['snippet_name'];  
    	return $arr;
	}    

	function get_countries_by_region($region) {
    	$db = new Db();
   		$arr[''] = 'Select Country';
    	$db->Query("SELECT id, country_name FROM country WHERE is_active=1 AND show_link=1 AND region=".$region." ORDER BY country_name");
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['country_name'];
    	}
    	return $arr;
	}

	function get_countries_from_db($addempty=FALSE, $type='all') {
    	$db = new Db();
    	$text = ($type!='all') ? 'Select Country' :'View All';
    	if ($addempty==TRUE)
    		$arr[''] = $text;
    	$db->Query("SELECT id, country_name FROM country WHERE is_active=1 ORDER BY country_name");
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['country_name'];
    	}
    	return $arr;
	}
	
	function get_country_from_db($sel) {
    	$db = new Db();
		$sel = settype($sel, 'integer');
    	$db->Query("SELECT country_name FROM country WHERE id=".$sel);
    	$data = $db->fetchAssoc();
    	return $data['country_name'];
	}

	function get_top_events($country, $addempty=FALSE, $str='Select Election') {
    	$db = new Db();
		$sfsql =& new SafeSQL_MySQL();
    	if ($addempty==TRUE)
    		$arr[''] = $str;
    	$db->Query("SELECT elections.id, CONCAT(election_name, 
        IF(elections.round_num>0, 
            CONCAT(' ', round_texts.status_text),
            '')
        ) as election_name 
        FROM elections 
        LEFT JOIN round_texts ON round_texts.id=elections.round_num 
        WHERE elections.parent=0 "
		. $sfsql->SafeCompose("AND elections.country=%s ", $country));
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}
	
	function get_all_events($country, $addempty=FALSE, $str='Select Election') {
    	$db = new Db();
		$sfsql =& new SafeSQL_MySQL();
    	if ($addempty==TRUE)
    		$arr[''] = $str;
    	$db->Query("SELECT id, election_name FROM elections WHERE " . $sfsql->SafeCompose(" country=%s", $country));
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}
	
	function get_events($addempty=FALSE, $str='Select Election') {
    	$db = new Db();
    	if ($addempty==TRUE)
    		$arr[''] = $str;
    	$db->Query("SELECT id, TRIM(election_name) AS election_name FROM elections ORDER BY election_name ASC");
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}

	function get_imports_for_country_type($country, $type, $me) {
    	$db = new Db();
        $arr = array();
    	$db->Query("SELECT elections.id,
        CONCAT(election_name,
            IF(round_num>0, CONCAT(' Round', round_num), ''),
            ' (', status_texts.status_text, ')') as election_name
        FROM elections, status_texts
        WHERE country=".$country."
        AND elections.election_type=".$type."
        AND status_texts.id=elections.is_active
        AND elections.id!=".$me."
        ORDER BY date_updated ASC");
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}
	
	function get_imports_for_country($country) {
    	$db = new Db();
        $arr = array();
    	$db->Query("SELECT elections.id,
        CONCAT(election_name, ' (', status_texts.status_text, ')') as election_name
        FROM elections, status_texts
        WHERE country=".$country." AND status_texts.id=elections.is_active
        ORDER BY date_updated ASC");
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}
	function get_events_for_country($sel) {
    	$db = new Db();
    	$arr[''] = 'Select Election';
    	$db->Query("SELECT id, election_name FROM elections WHERE country=".$sel);
    	while($data = $db->fetchAssoc()) {
    		$arr[$data['id']] = $data['election_name'];
    	}
    	return $arr;
	}
	
	function build_airport_array($prevfld, $array) {
		$ret = '<script type="text/javascript">
		if (!assocArray) 
			var assocArray = new Object();';

			foreach($array as $country => $values) {
				$ret .= "\n".'assocArray["'.$prevfld.'='.$country.'"] = new Array(';	
					foreach($values as $value) {
						$ret .= '"'.$value['code'].'","'.$value['airportname'].'",';		
					}
				$ret .= '"EOF");'."\n";
			}

		$ret .= '</script>';
		return $ret;
	}

    function select_multi_item($name, $array, $selected, $extra='') {
        if (is_array($array)) {
            $ret = '<select name="'.$name.'[]" size="6" id="'.$name.'"'.$extra.' multiple="multiple">';
            foreach($array as $key => $value) {
            	if (is_array($selected)) {
            		$chk = (in_array($key, $selected)) ? ' selected="selected"': '';	
            	} else {
                	$chk = ($selected==$key) ? ' selected="selected"': '';
            	}
                $ret .= '<option value="'.$key.'"'.$chk.'>'.$value.'</option>'."\n";
            }
            $ret .= '</select>';
        }
        return $ret;
    }

    function select_item($name, $array, $selected, $extra='') {
        if (is_array($array)) {
            $ret = '<select name="'.$name.'" size="1" id="'.$name.'"'.$extra.'>';
            foreach($array as $key => $value) {
                $chk = ($selected==$key) ? ' selected="selected"': '';
                $ret .= '<option value="'.$key.'"'.$chk.'>'.$value.'</option>'."\n";
            }
            $ret .= '</select>';
        } 
        return $ret;
    }

    function select_item_simple($name, $array, $selected, $extra='') {
        if (is_array($array)) {
            $ret = '<select name="'.$name.'" size="1" id="'.$name.'"'.$extra.'>';
            foreach($array as $value) {
                $chk = ($selected==$value) ? ' selected="selected"': '';
                $ret .= '<option value="'.$value.'"'.$chk.'>'.$value.'</option>'."\n";
            }
            $ret .= '</select>';
        }
        return $ret;
    }

    function generate_auth($len) {
       $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $length = $len;
       $password="";

       while($length>0) {
           $password .= $valid_chars[rand(0,strlen($valid_chars)-1)];
           $length--;
       }

       return $password;
    }

    function generate_pass() {
       $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $length = 16;
       $password="";

       while($length>0) {
           $password .= $valid_chars[rand(0,strlen($valid_chars)-1)];
           $length--;
       }

       return $password;
    }

    function generate_dbpass() {
       $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $length = 8;
       $password="";

       while($length>0) {
           $password .= $valid_chars[rand(0,strlen($valid_chars)-1)];
           $length--;
       }

       return $password;
    }

    function test_imgupload($field) {
        $approved_types = array('image/gif', 'image/jpeg', 'image/jpg');
        if($_FILES[$field]['name']!='') {
            if (in_array($_FILES[$field]['type'], $approved_types)) {
                return 'ok';
            }  else {
                return 'file';
            }
        }
    }

    function resampimagejpg($forcedwidth, $forcedheight, $sourcefile, $destfile, $imgcomp) {
       $g_imgcomp=100-$imgcomp;
       $g_srcfile=$sourcefile;
       $g_dstfile=$destfile;
       $g_fw=$forcedwidth;
       $g_fh=$forcedheight;

       if(file_exists($g_srcfile)) {
           $g_is = getimagesize($g_srcfile);
           if(($g_is[0]-$g_fw)>=($g_is[1]-$g_fh)){
               $g_iw=$g_fw;
               $g_ih=($g_fw/$g_is[0])*$g_is[1];
           } else {
               $g_ih=$g_fh;
               $g_iw=($g_ih/$g_is[1])*$g_is[0];
           }
       
               $img_src=imagecreatefromjpeg($g_srcfile);
               $img_dst=imagecreatetruecolor($g_iw,$g_ih);
               imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
               imagejpeg($img_dst, $g_dstfile, $g_imgcomp);
               imagedestroy($img_dst);
               return true;
       }  else  {
           return false;
       }
    }
    
    function upload_logo($field, $filedir) {
        if($_FILES[$field]['name']!='') {
            $destfile = $filedir.$_FILES[$field]['name'];
            //echo $destfile;
            if (file_exists($destfile)) {
                unlink($destfile);
            }
            $thumbfile = '../images/flags/'.$_FILES[$field]['name'];
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $destfile)) {
                //Common::resampimagejpg(50, 25, $destfile, $thumbfile, 10);
                return 'ok';
            } else {
                return 'location';
            }
        }
    }

    function test_upload($field) {
        $approved_types = array('application/pdf');
        if($_FILES[$field]['name']!='') {
            if (in_array($_FILES[$field]['type'], $approved_types)) {
                return 'ok';
            }  else {
                return 'file';
            }
        }
    }

    function upload_file($field, $fileid) {
        $filedir = './pdfs/';
        if($_FILES[$field]['name']!='') {
            $ret = Common::test_upload($field);
            if ($ret=='ok') {
                $destfile = $filedir.'contract_'.$fileid.'.pdf';

                if (file_exists($destfile)) {
                    unlink($destfile);
                    //echo 'unlinked';
                }

                if (move_uploaded_file($_FILES[$field]['tmp_name'], $destfile)) {
                    return 'ok';
                } else {
                    return 'location';
                }
            } else {
                return $ret;
            }
        }
    }

    function string_shuffle($word){
       for($i=0;$i<strlen($word);$i++)
           $array[]=$word[$i];

       shuffle($array);
       for($i=0;$i<count($array);$i++)
           $newstring .= $array[$i];

       return $newstring;
   }

   function generate_password($length){
        srand((double)microtime()*1000000);
        $vowels = array("a", "e", "i", "o", "u");
        $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
        "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");

        $num_vowels = count($vowels);
        $num_cons = count($cons);

        for($i = 0; $i < $length; $i++){
            $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
        }

        return substr($password, 0, $length);
    }

   function generate_username($word){
        $username = ereg_replace('[^[:alnum:]]', '', $word);
        return $username;
    }

   function mail_admin($msg) {
        require_once("phpclasses/class.phpmailer.php");
        require_once("phpclasses/Mailer.Class.php");

        $email_to               = 'paivi@esitemarketing.com';
        $email_reply_address    = 'paivi@esitemarketing.com';
        $email_from_name        = 'IPS - '.CLIENT_NAME;
        $email_subject          = 'IPS Stats';
        $email_body             = $msg;
        $email_isHTML           = true;
        $email_AltBody          = '';

        $mail = new Mailer($email_to, $email_reply_address, $email_from_name, $email_subject, $email_body, $AltBody, $email_isHTML);
        $sent = $mail->sendMail();
    }
    
    function html_decode($mystr) {
        $entities = get_html_translation_table (HTML_ENTITIES);
        $specialchars = get_html_translation_table (HTML_SPECIALCHARS);

        foreach($entities as $key => $value){
           if (!in_array($value, $specialchars)) {
                $mystr = str_replace($key, $value, $mystr);
           }
       }
       return $mystr;
    }
    
    function strip_them_slashes($data) {
        $data = html_entity_decode($data);
        /*
        if ($data!='') {
            $NewEnc = new ConvertCharset;
            $data = $NewEnc->Convert($data, 'iso-8859-1', 'utf-8');
        }
        */
        if (get_magic_quotes_gpc()==1) {
            return stripslashes($data);
        } else {
            return $data;
        }
    }

    function add_them_slashes($dat) {
        $data = Common::htmldecode($dat);
        
        //$NewEnc = new ConvertCharset;
        //$data = $NewEnc->Convert($data, 'iso-8859-1', 'utf-8');
        if (get_magic_quotes_gpc()==1) {
            return $data;
        } else {
            return addslashes($data);
        }
    }
}

?>
