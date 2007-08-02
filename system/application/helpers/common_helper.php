<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Common Helper
 *
 * @package		AGOv3
 * @subpackage	Helpers
 * @category	Misc functionalities
 * @author		Bruno Santos
 */

// ------------------------------------------------------------------------

/**
 * redirect_external
 *
 * The same as redirect but for an external url.
 *
 * Prototype: redirect_external("url", 'method');
 * Example:	  display("http://example.com", 'refresh');
 *
 * @access	public
 * @param	string	redirect url
 * @param	string	redirect method: refresh or location
 * @return	void	
 */
function redirect_external($uri = '', $method = 'location')
{

    switch($method)
    {
        case 'refresh': header("Refresh:0;url=".$uri);
            break;
        default       : header("location:".$uri);
            break;
    }
    exit;
}

function country_select($name,$selected=null,$js=null){
	$country_options = array(
		'' => '--',
		'US' => 'United States',
		 'AF' => 'Afghanistan',
		 'AX' => 'Aland Islands',
		 'AL' => 'Albania',

		 'DZ' => 'Algeria',
		 'AS' => 'American Samoa',
		 'AD' => 'Andorra',
		 'AO' => 'Angola',
		 'AI' => 'Anguilla',
		 'AQ' => 'Antarctica',

		 'AG' => 'Antigua and Barbuda',
		 'AR' => 'Argentina',
		 'AM' => 'Armenia',
		 'AW' => 'Aruba',
		 'AU' => 'Australia',
		 'AT' => 'Austria',

		 'AZ' => 'Azerbaijan',
		 'BS' => 'Bahamas',
		 'BH' => 'Bahrain',
		 'BD' => 'Bangladesh',
		 'BB' => 'Barbados',
		 'BY' => 'Belarus',

		 'BE' => 'Belgium',
		 'BZ' => 'Belize',
		 'BJ' => 'Benin',
		 'BM' => 'Bermuda',
		 'BT' => 'Bhutan',
		 'BO' => 'Bolivia',

		 'BA' => 'Bosnia And Herzegovina',
		 'BW' => 'Botswana',
		 'BV' => 'Bouvet Island',
		 'BR' => 'Brazil',
		 'IO' => 'British Indian Ocean Territory',
		 'BN' => 'Brunei Darussalam',

		 'BG' => 'Bulgaria',
		 'BF' => 'Burkina Faso',
		 'BI' => 'Burundi',
		 'KH' => 'Cambodia',
		 'CM' => 'Cameroon',
		 'CA' => 'Canada',

		 'CV' => 'Cape Verde',
		 'KY' => 'Cayman Islands',
		 'CF' => 'Central African Republic',
		 'TD' => 'Chad',
		 'CL' => 'Chile',
		 'CN' => 'China',

		 'CX' => 'Christmas Island',
		 'CC' => 'Cocos (Keeling) Islands',
		 'CO' => 'Colombia',
		 'KM' => 'Comoros',
		 'CG' => 'Congo',
		 'CD' => 'Congo, The Democratic Republic Of The',

		 'CK' => 'Cook Islands',
		 'CR' => 'Costa Rica',
		 'CI' => 'Cote D\'Ivoire',
		 'HR' => 'Croatia',
		 'CU' => 'Cuba',
		 'CY' => 'Cyprus',

		 'CZ' => 'Czech Republic',
		 'DK' => 'Denmark',
		 'DJ' => 'Djibouti',
		 'DM' => 'Dominica',
		 'DO' => 'Dominican Republic',
		 'EC' => 'Ecuador',

		 'EG' => 'Egypt',
		 'SV' => 'El Salvador',
		 'GQ' => 'Equatorial Guinea',
		 'ER' => 'Eritrea',
		 'EE' => 'Estonia',
		 'ET' => 'Ethiopia',

		 'FK' => 'Falkland Islands (Malvinas)',
		 'FO' => 'Faroe Islands',
		 'FJ' => 'Fiji',
		 'FI' => 'Finland',
		 'FR' => 'France',
		 'GF' => 'French Guiana',

		 'PF' => 'French Polynesia',
		 'TF' => 'French Southern Territories',
		 'GA' => 'Gabon',
		 'GM' => 'Gambia',
		 'GE' => 'Georgia',
		 'DE' => 'Germany',

		 'GH' => 'Ghana',
		 'GI' => 'Gibraltar',
		 'GR' => 'Greece',
		 'GL' => 'Greenland',
		 'GD' => 'Grenada',
		 'GP' => 'Guadeloupe',

		 'GU' => 'Guam',
		 'GT' => 'Guatemala',
		 'GN' => 'Guinea',
		 'GW' => 'Guinea-Bissau',
		 'GY' => 'Guyana',
		 'HT' => 'Haiti',

		 'HM' => 'Heard Island and McDonald Islands',
		 'VA' => 'Holy See (Vatican City State)',
		 'HN' => 'Honduras',
		 'HK' => 'Hong Kong',
		 'HU' => 'Hungary',
		 'IS' => 'Iceland',

		 'IN' => 'India',
		 'ID' => 'Indonesia',
		 'IR' => 'Iran, Islamic Republic Of',
		 'IQ' => 'Iraq',
		 'IE' => 'Ireland',
		 'IL' => 'Israel',

		 'IT' => 'Italy',
		 'JM' => 'Jamaica',
		 'JP' => 'Japan',
		 'JO' => 'Jordan',
		 'KZ' => 'Kazakhstan',
		 'KE' => 'Kenya',

		 'KI' => 'Kiribati',
		 'KP' => 'Korea, Democratic People\'s Republic Of',
		 'KR' => 'Korea, Republic Of',
		 'KW' => 'Kuwait',
		 'KG' => 'Kyrgyzstan',
		 'LA' => 'Lao People\'s Democratic Republic',

		 'LV' => 'Latvia',
		 'LB' => 'Lebanon',
		 'LS' => 'Lesotho',
		 'LR' => 'Liberia',
		 'LY' => 'Libyan Arab Jamahiriya',
		 'LI' => 'Liechtenstein',

		 'LT' => 'Lithuania',
		 'LU' => 'Luxembourg',
		 'MO' => 'Macao',
		 'MK' => 'Macedonia, The Former Yugoslav Republic Of',
		 'MG' => 'Madagascar',
		 'MW' => 'Malawi',

		 'MY' => 'Malaysia',
		 'MV' => 'Maldives',
		 'ML' => 'Mali',
		 'MT' => 'Malta',
		 'MH' => 'Marshall Islands',
		 'MQ' => 'Martinique',

		 'MR' => 'Mauritania',
		 'MU' => 'Mauritius',
		 'YT' => 'Mayotte',
		 'MX' => 'Mexico',
		 'FM' => 'Micronesia, Federated States Of',
		 'MD' => 'Moldova, Republic Of',

		 'MC' => 'Monaco',
		 'MN' => 'Mongolia',
		 'ME' => 'Montenegro',
		 'MS' => 'Montserrat',
		 'MA' => 'Morocco',
		 'MZ' => 'Mozambique',

		 'MM' => 'Myanmar',
		 'NA' => 'Namibia',
		 'NR' => 'Nauru',
		 'NP' => 'Nepal',
		 'NL' => 'Netherlands',
		 'AN' => 'Netherlands Antilles',

		 'NC' => 'New Caledonia',
		 'NZ' => 'New Zealand',
		 'NI' => 'Nicaragua',
		 'NE' => 'Niger',
		 'NG' => 'Nigeria',
		 'NU' => 'Niue',

		 'NF' => 'Norfolk Island',
		 'MP' => 'Northern Mariana Islands',
		 'NO' => 'Norway',
		 'OM' => 'Oman',
		 'PK' => 'Pakistan',
		 'PW' => 'Palau',

		 'PS' => 'Palestinian Territory, Occupied',
		 'PA' => 'Panama',
		 'PG' => 'Papua New Guinea',
		 'PY' => 'Paraguay',
		 'PE' => 'Peru',
		 'PH' => 'Philippines',

		 'PN' => 'Pitcairn',
		 'PL' => 'Poland',
		 'PT' => 'Portugal',
		 'PR' => 'Puerto Rico',
		 'QA' => 'Qatar',
		 'RE' => 'Reunion',

		 'RO' => 'Romania',
		 'RU' => 'Russian Federation',
		 'RW' => 'Rwanda',
		 'SH' => 'Saint Helena',
		 'KN' => 'Saint Kitts And Nevis',
		 'LC' => 'Saint Lucia',

		 'PM' => 'Saint Pierre And Miquelon',
		 'VC' => 'Saint Vincent And The Grenadines',
		 'WS' => 'Samoa',
		 'SM' => 'San Marino',
		 'ST' => 'Sao Tome And Principe',
		 'SA' => 'Saudi Arabia',

		 'SN' => 'Senegal',
		 'RS' => 'Serbia',
		 'SC' => 'Seychelles',
		 'SL' => 'Sierra Leone',
		 'SG' => 'Singapore',
		 'SK' => 'Slovakia',

		 'SI' => 'Slovenia',
		 'SB' => 'Solomon Islands',
		 'SO' => 'Somalia',
		 'ZA' => 'South Africa',
		 'GS' => 'South Georgia And The South Sandwich Islands',
		 'ES' => 'Spain',

		 'LK' => 'Sri Lanka',
		 'SD' => 'Sudan',
		 'SR' => 'Suriname',
		 'SJ' => 'Svalbard And Jan Mayen',
		 'SZ' => 'Swaziland',
		 'SE' => 'Sweden',

		 'CH' => 'Switzerland',
		 'SY' => 'Syrian Arab Republic',
		 'TW' => 'Taiwan',
		 'TJ' => 'Tajikistan',
		 'TZ' => 'Tanzania, United Republic Of',
		 'TH' => 'Thailand',

		 'TL' => 'Timor-leste',
		 'TG' => 'Togo',
		 'TK' => 'Tokelau',
		 'TO' => 'Tonga',
		 'TT' => 'Trinidad And Tobago',
		 'TN' => 'Tunisia',

		 'TR' => 'Turkey',
		 'TM' => 'Turkmenistan',
		 'TC' => 'Turks And Caicos Islands',
		 'TV' => 'Tuvalu',
		 'UG' => 'Uganda',
		 'UA' => 'Ukraine',

		 'AE' => 'United Arab Emirates',
		 'GB' => 'United Kingdom',
		 'UM' => 'United States Minor Outlying Islands',
		 'UY' => 'Uruguay',
		 'UZ' => 'Uzbekistan',
		 'VU' => 'Vanuatu',

		 'VE' => 'Venezuela',
		 'VN' => 'Viet Nam',
		 'VG' => 'Virgin Islands, British',
		 'VI' => 'Virgin Islands, U.S.',
		 'WF' => 'Wallis And Futuna',
		 'EH' => 'Western Sahara',

		 'YE' => 'Yemen',
		 'ZM' => 'Zambia',
		 'ZW' => 'Zimbabwe'
		);
		
		echo form_dropdown($name, $country_options, $selected, $js);
}

function select_timezone($name,$selected=null,$js=null){
	$tz_options = array(
	 '' => '--',
	 'Africa/Abidjan' => 'Africa/Abidjan',
	 'Africa/Accra' => 'Africa/Accra',
	 'Africa/Addis_Ababa' => 'Africa/Addis_Ababa',
	 'Africa/Algiers' => 'Africa/Algiers',

	 'Africa/Asmera' => 'Africa/Asmera',
	 'Africa/Bamako' => 'Africa/Bamako',
	 'Africa/Bangui' => 'Africa/Bangui',
	 'Africa/Banjul' => 'Africa/Banjul',
	 'Africa/Bissau' => 'Africa/Bissau',
	 'Africa/Blantyre' => 'Africa/Blantyre',

	 'Africa/Brazzaville' => 'Africa/Brazzaville',
	 'Africa/Bujumbura' => 'Africa/Bujumbura',
	 'Africa/Cairo' => 'Africa/Cairo',
	 'Africa/Casablanca' => 'Africa/Casablanca',
	 'Africa/Ceuta' => 'Africa/Ceuta',
	 'Africa/Conakry' => 'Africa/Conakry',

	 'Africa/Dakar' => 'Africa/Dakar',
	 'Africa/Dar_es_Salaam' => 'Africa/Dar_es_Salaam',
	 'Africa/Djibouti' => 'Africa/Djibouti',
	 'Africa/Douala' => 'Africa/Douala',
	 'Africa/El_Aaiun' => 'Africa/El_Aaiun',
	 'Africa/Freetown' => 'Africa/Freetown',

	 'Africa/Gaborone' => 'Africa/Gaborone',
	 'Africa/Harare' => 'Africa/Harare',
	 'Africa/Johannesburg' => 'Africa/Johannesburg',
	 'Africa/Kampala' => 'Africa/Kampala',
	 'Africa/Khartoum' => 'Africa/Khartoum',
	 'Africa/Kigali' => 'Africa/Kigali',

	 'Africa/Kinshasa' => 'Africa/Kinshasa',
	 'Africa/Lagos' => 'Africa/Lagos',
	 'Africa/Libreville' => 'Africa/Libreville',
	 'Africa/Lome' => 'Africa/Lome',
	 'Africa/Luanda' => 'Africa/Luanda',
	 'Africa/Lubumbashi' => 'Africa/Lubumbashi',

	 'Africa/Lusaka' => 'Africa/Lusaka',
	 'Africa/Malabo' => 'Africa/Malabo',
	 'Africa/Maputo' => 'Africa/Maputo',
	 'Africa/Maseru' => 'Africa/Maseru',
	 'Africa/Mbabane' => 'Africa/Mbabane',
	 'Africa/Mogadishu' => 'Africa/Mogadishu',

	 'Africa/Monrovia' => 'Africa/Monrovia',
	 'Africa/Nairobi' => 'Africa/Nairobi',
	 'Africa/Ndjamena' => 'Africa/Ndjamena',
	 'Africa/Niamey' => 'Africa/Niamey',
	 'Africa/Nouakchott' => 'Africa/Nouakchott',
	 'Africa/Ouagadougou' => 'Africa/Ouagadougou',

	 'Africa/Porto' => 'Africa/Porto',
	 'Africa/Sao_Tome' => 'Africa/Sao_Tome',
	 'Africa/Tripoli' => 'Africa/Tripoli',
	 'Africa/Tunis' => 'Africa/Tunis',
	 'Africa/Windhoek' => 'Africa/Windhoek',
	 'America/Adak' => 'America/Adak',

	 'America/Anchorage' => 'America/Anchorage',
	 'America/Anguilla' => 'America/Anguilla',
	 'America/Antigua' => 'America/Antigua',
	 'America/Araguaina' => 'America/Araguaina',
	 'America/Argentina/Buenos_Aires' => 'America/Argentina/Buenos_Aires',
	 'America/Argentina/Catamarca' => 'America/Argentina/Catamarca',

	 'America/Argentina/Cordoba' => 'America/Argentina/Cordoba',
	 'America/Argentina/Jujuy' => 'America/Argentina/Jujuy',
	 'America/Argentina/La_Rioja' => 'America/Argentina/La_Rioja',
	 'America/Argentina/Mendoza' => 'America/Argentina/Mendoza',
	 'America/Argentina/Rio_Gallegos' => 'America/Argentina/Rio_Gallegos',
	 'America/Argentina/San_Juan' => 'America/Argentina/San_Juan',

	 'America/Argentina/Tucuman' => 'America/Argentina/Tucuman',
	 'America/Argentina/Ushuaia' => 'America/Argentina/Ushuaia',
	 'America/Aruba' => 'America/Aruba',
	 'America/Asuncion' => 'America/Asuncion',
	 'America/Bahia' => 'America/Bahia',
	 'America/Barbados' => 'America/Barbados',

	 'America/Belem' => 'America/Belem',
	 'America/Belize' => 'America/Belize',
	 'America/Boa_Vista' => 'America/Boa_Vista',
	 'America/Bogota' => 'America/Bogota',
	 'America/Boise' => 'America/Boise',
	 'America/Cambridge_Bay' => 'America/Cambridge_Bay',

	 'America/Campo_Grande' => 'America/Campo_Grande',
	 'America/Cancun' => 'America/Cancun',
	 'America/Caracas' => 'America/Caracas',
	 'America/Cayenne' => 'America/Cayenne',
	 'America/Cayman' => 'America/Cayman',
	 'America/Chicago' => 'America/Chicago',

	 'America/Chihuahua' => 'America/Chihuahua',
	 'America/Coral_Harbour' => 'America/Coral_Harbour',
	 'America/Costa_Rica' => 'America/Costa_Rica',
	 'America/Cuiaba' => 'America/Cuiaba',
	 'America/Curacao' => 'America/Curacao',
	 'America/Danmarkshavn' => 'America/Danmarkshavn',

	 'America/Dawson' => 'America/Dawson',
	 'America/Dawson_Creek' => 'America/Dawson_Creek',
	 'America/Denver' => 'America/Denver',
	 'America/Detroit' => 'America/Detroit',
	 'America/Dominica' => 'America/Dominica',
	 'America/Edmonton' => 'America/Edmonton',

	 'America/Eirunepe' => 'America/Eirunepe',
	 'America/El_Salvador' => 'America/El_Salvador',
	 'America/Fortaleza' => 'America/Fortaleza',
	 'America/Glace_Bay' => 'America/Glace_Bay',
	 'America/Godthab' => 'America/Godthab',
	 'America/Goose_Bay' => 'America/Goose_Bay',

	 'America/Grand_Turk' => 'America/Grand_Turk',
	 'America/Grenada' => 'America/Grenada',
	 'America/Guadeloupe' => 'America/Guadeloupe',
	 'America/Guatemala' => 'America/Guatemala',
	 'America/Guayaquil' => 'America/Guayaquil',
	 'America/Guyana' => 'America/Guyana',

	 'America/Halifax' => 'America/Halifax',
	 'America/Havana' => 'America/Havana',
	 'America/Hermosillo' => 'America/Hermosillo',
	 'America/Indiana/Indianapolis' => 'America/Indiana/Indianapolis',
	 'America/Indiana/Knox' => 'America/Indiana/Knox',
	 'America/Indiana/Marengo' => 'America/Indiana/Marengo',

	 'America/Indiana/Petersburg' => 'America/Indiana/Petersburg',
	 'America/Indiana/Vevay' => 'America/Indiana/Vevay',
	 'America/Indiana/Vincennes' => 'America/Indiana/Vincennes',
	 'America/Inuvik' => 'America/Inuvik',
	 'America/Iqaluit' => 'America/Iqaluit',
	 'America/Jamaica' => 'America/Jamaica',

	 'America/Juneau' => 'America/Juneau',
	 'America/Kentucky/Louisville' => 'America/Kentucky/Louisville',
	 'America/Kentucky/Monticello' => 'America/Kentucky/Monticello',
	 'America/La_Paz' => 'America/La_Paz',
	 'America/Lima' => 'America/Lima',
	 'America/Los_Angeles' => 'America/Los_Angeles',

	 'America/Maceio' => 'America/Maceio',
	 'America/Managua' => 'America/Managua',
	 'America/Manaus' => 'America/Manaus',
	 'America/Martinique' => 'America/Martinique',
	 'America/Mazatlan' => 'America/Mazatlan',
	 'America/Menominee' => 'America/Menominee',

	 'America/Merida' => 'America/Merida',
	 'America/Mexico_City' => 'America/Mexico_City',
	 'America/Miquelon' => 'America/Miquelon',
	 'America/Moncton' => 'America/Moncton',
	 'America/Monterrey' => 'America/Monterrey',
	 'America/Montevideo' => 'America/Montevideo',

	 'America/Montreal' => 'America/Montreal',
	 'America/Montserrat' => 'America/Montserrat',
	 'America/Nassau' => 'America/Nassau',
	 'America/New_York' => 'America/New_York',
	 'America/Nipigon' => 'America/Nipigon',
	 'America/Nome' => 'America/Nome',

	 'America/Noronha' => 'America/Noronha',
	 'America/North_Dakota/Center' => 'America/North_Dakota/Center',
	 'America/Panama' => 'America/Panama',
	 'America/Pangnirtung' => 'America/Pangnirtung',
	 'America/Paramaribo' => 'America/Paramaribo',
	 'America/Phoenix' => 'America/Phoenix',

	 'America/Port' => 'America/Port',
	 'America/Port_of_Spain' => 'America/Port_of_Spain',
	 'America/Porto_Velho' => 'America/Porto_Velho',
	 'America/Puerto_Rico' => 'America/Puerto_Rico',
	 'America/Rainy_River' => 'America/Rainy_River',
	 'America/Rankin_Inlet' => 'America/Rankin_Inlet',

	 'America/Recife' => 'America/Recife',
	 'America/Regina' => 'America/Regina',
	 'America/Rio_Branco' => 'America/Rio_Branco',
	 'America/Santiago' => 'America/Santiago',
	 'America/Santo_Domingo' => 'America/Santo_Domingo',
	 'America/Sao_Paulo' => 'America/Sao_Paulo',

	 'America/Scoresbysund' => 'America/Scoresbysund',
	 'America/Shiprock' => 'America/Shiprock',
	 'America/St_Johns' => 'America/St_Johns',
	 'America/St_Kitts' => 'America/St_Kitts',
	 'America/St_Lucia' => 'America/St_Lucia',
	 'America/St_Thomas' => 'America/St_Thomas',

	 'America/St_Vincent' => 'America/St_Vincent',
	 'America/Swift_Current' => 'America/Swift_Current',
	 'America/Tegucigalpa' => 'America/Tegucigalpa',
	 'America/Thule' => 'America/Thule',
	 'America/Thunder_Bay' => 'America/Thunder_Bay',
	 'America/Tijuana' => 'America/Tijuana',

	 'America/Toronto' => 'America/Toronto',
	 'America/Tortola' => 'America/Tortola',
	 'America/Vancouver' => 'America/Vancouver',
	 'America/Whitehorse' => 'America/Whitehorse',
	 'America/Winnipeg' => 'America/Winnipeg',
	 'America/Yakutat' => 'America/Yakutat',

	 'America/Yellowknife' => 'America/Yellowknife',
	 'Antarctica/Casey' => 'Antarctica/Casey',
	 'Antarctica/Davis' => 'Antarctica/Davis',
	 'Antarctica/DumontDUrville' => 'Antarctica/DumontDUrville',
	 'Antarctica/Mawson' => 'Antarctica/Mawson',
	 'Antarctica/McMurdo' => 'Antarctica/McMurdo',

	 'Antarctica/Palmer' => 'Antarctica/Palmer',
	 'Antarctica/Rothera' => 'Antarctica/Rothera',
	 'Antarctica/South_Pole' => 'Antarctica/South_Pole',
	 'Antarctica/Syowa' => 'Antarctica/Syowa',
	 'Antarctica/Vostok' => 'Antarctica/Vostok',
	 'Arctic/Longyearbyen' => 'Arctic/Longyearbyen',

	 'Asia/Aden' => 'Asia/Aden',
	 'Asia/Almaty' => 'Asia/Almaty',
	 'Asia/Amman' => 'Asia/Amman',
	 'Asia/Anadyr' => 'Asia/Anadyr',
	 'Asia/Aqtau' => 'Asia/Aqtau',
	 'Asia/Aqtobe' => 'Asia/Aqtobe',

	 'Asia/Ashgabat' => 'Asia/Ashgabat',
	 'Asia/Baghdad' => 'Asia/Baghdad',
	 'Asia/Bahrain' => 'Asia/Bahrain',
	 'Asia/Baku' => 'Asia/Baku',
	 'Asia/Bangkok' => 'Asia/Bangkok',
	 'Asia/Beirut' => 'Asia/Beirut',

	 'Asia/Bishkek' => 'Asia/Bishkek',
	 'Asia/Brunei' => 'Asia/Brunei',
	 'Asia/Calcutta' => 'Asia/Calcutta',
	 'Asia/Choibalsan' => 'Asia/Choibalsan',
	 'Asia/Chongqing' => 'Asia/Chongqing',
	 'Asia/Colombo' => 'Asia/Colombo',

	 'Asia/Damascus' => 'Asia/Damascus',
	 'Asia/Dhaka' => 'Asia/Dhaka',
	 'Asia/Dili' => 'Asia/Dili',
	 'Asia/Dubai' => 'Asia/Dubai',
	 'Asia/Dushanbe' => 'Asia/Dushanbe',
	 'Asia/Gaza' => 'Asia/Gaza',

	 'Asia/Harbin' => 'Asia/Harbin',
	 'Asia/Hong_Kong' => 'Asia/Hong_Kong',
	 'Asia/Hovd' => 'Asia/Hovd',
	 'Asia/Irkutsk' => 'Asia/Irkutsk',
	 'Asia/Jakarta' => 'Asia/Jakarta',
	 'Asia/Jayapura' => 'Asia/Jayapura',

	 'Asia/Jerusalem' => 'Asia/Jerusalem',
	 'Asia/Kabul' => 'Asia/Kabul',
	 'Asia/Kamchatka' => 'Asia/Kamchatka',
	 'Asia/Karachi' => 'Asia/Karachi',
	 'Asia/Kashgar' => 'Asia/Kashgar',
	 'Asia/Katmandu' => 'Asia/Katmandu',

	 'Asia/Krasnoyarsk' => 'Asia/Krasnoyarsk',
	 'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
	 'Asia/Kuching' => 'Asia/Kuching',
	 'Asia/Kuwait' => 'Asia/Kuwait',
	 'Asia/Macau' => 'Asia/Macau',
	 'Asia/Magadan' => 'Asia/Magadan',

	 'Asia/Makassar' => 'Asia/Makassar',
	 'Asia/Manila' => 'Asia/Manila',
	 'Asia/Muscat' => 'Asia/Muscat',
	 'Asia/Nicosia' => 'Asia/Nicosia',
	 'Asia/Novosibirsk' => 'Asia/Novosibirsk',
	 'Asia/Omsk' => 'Asia/Omsk',

	 'Asia/Oral' => 'Asia/Oral',
	 'Asia/Phnom_Penh' => 'Asia/Phnom_Penh',
	 'Asia/Pontianak' => 'Asia/Pontianak',
	 'Asia/Pyongyang' => 'Asia/Pyongyang',
	 'Asia/Qatar' => 'Asia/Qatar',
	 'Asia/Qyzylorda' => 'Asia/Qyzylorda',

	 'Asia/Rangoon' => 'Asia/Rangoon',
	 'Asia/Riyadh' => 'Asia/Riyadh',
	 'Asia/Saigon' => 'Asia/Saigon',
	 'Asia/Sakhalin' => 'Asia/Sakhalin',
	 'Asia/Samarkand' => 'Asia/Samarkand',
	 'Asia/Seoul' => 'Asia/Seoul',

	 'Asia/Shanghai' => 'Asia/Shanghai',
	 'Asia/Singapore' => 'Asia/Singapore',
	 'Asia/Taipei' => 'Asia/Taipei',
	 'Asia/Tashkent' => 'Asia/Tashkent',
	 'Asia/Tbilisi' => 'Asia/Tbilisi',
	 'Asia/Tehran' => 'Asia/Tehran',

	 'Asia/Thimphu' => 'Asia/Thimphu',
	 'Asia/Tokyo' => 'Asia/Tokyo',
	 'Asia/Ulaanbaatar' => 'Asia/Ulaanbaatar',
	 'Asia/Urumqi' => 'Asia/Urumqi',
	 'Asia/Vientiane' => 'Asia/Vientiane',
	 'Asia/Vladivostok' => 'Asia/Vladivostok',

	 'Asia/Yakutsk' => 'Asia/Yakutsk',
	 'Asia/Yekaterinburg' => 'Asia/Yekaterinburg',
	 'Asia/Yerevan' => 'Asia/Yerevan',
	 'Atlantic/Azores' => 'Atlantic/Azores',
	 'Atlantic/Bermuda' => 'Atlantic/Bermuda',
	 'Atlantic/Canary' => 'Atlantic/Canary',

	 'Atlantic/Cape_Verde' => 'Atlantic/Cape_Verde',
	 'Atlantic/Faeroe' => 'Atlantic/Faeroe',
	 'Atlantic/Jan_Mayen' => 'Atlantic/Jan_Mayen',
	 'Atlantic/Madeira' => 'Atlantic/Madeira',
	 'Atlantic/Reykjavik' => 'Atlantic/Reykjavik',
	 'Atlantic/South_Georgia' => 'Atlantic/South_Georgia',

	 'Atlantic/St_Helena' => 'Atlantic/St_Helena',
	 'Atlantic/Stanley' => 'Atlantic/Stanley',
	 'Australia/Adelaide' => 'Australia/Adelaide',
	 'Australia/Brisbane' => 'Australia/Brisbane',
	 'Australia/Broken_Hill' => 'Australia/Broken_Hill',
	 'Australia/Currie' => 'Australia/Currie',

	 'Australia/Darwin' => 'Australia/Darwin',
	 'Australia/Hobart' => 'Australia/Hobart',
	 'Australia/Lindeman' => 'Australia/Lindeman',
	 'Australia/Lord_Howe' => 'Australia/Lord_Howe',
	 'Australia/Melbourne' => 'Australia/Melbourne',
	 'Australia/Perth' => 'Australia/Perth',

	 'Australia/Sydney' => 'Australia/Sydney',
	 'Europe/Amsterdam' => 'Europe/Amsterdam',
	 'Europe/Andorra' => 'Europe/Andorra',
	 'Europe/Athens' => 'Europe/Athens',
	 'Europe/Belgrade' => 'Europe/Belgrade',
	 'Europe/Berlin' => 'Europe/Berlin',

	 'Europe/Bratislava' => 'Europe/Bratislava',
	 'Europe/Brussels' => 'Europe/Brussels',
	 'Europe/Bucharest' => 'Europe/Bucharest',
	 'Europe/Budapest' => 'Europe/Budapest',
	 'Europe/Chisinau' => 'Europe/Chisinau',
	 'Europe/Copenhagen' => 'Europe/Copenhagen',

	 'Europe/Dublin' => 'Europe/Dublin',
	 'Europe/Gibraltar' => 'Europe/Gibraltar',
	 'Europe/Helsinki' => 'Europe/Helsinki',
	 'Europe/Istanbul' => 'Europe/Istanbul',
	 'Europe/Kaliningrad' => 'Europe/Kaliningrad',
	 'Europe/Kiev' => 'Europe/Kiev',

	 'Europe/Lisbon' =>'Europe/Lisbon',
	 'Europe/Ljubljana' => 'Europe/Ljubljana',
	 'Europe/London' => 'Europe/London',
	 'Europe/Luxembourg' => 'Europe/Luxembourg',
	 'Europe/Madrid' => 'Europe/Madrid',
	 'Europe/Malta' => 'Europe/Malta',

	 'Europe/Mariehamn' => 'Europe/Mariehamn',
	 'Europe/Minsk' => 'Europe/Minsk',
	 'Europe/Monaco' => 'Europe/Monaco',
	 'Europe/Moscow' => 'Europe/Moscow',
	 'Europe/Oslo' => 'Europe/Oslo',
	 'Europe/Paris' => 'Europe/Paris',

	 'Europe/Prague' => 'Europe/Prague',
	 'Europe/Riga' => 'Europe/Riga',
	 'Europe/Rome' => 'Europe/Rome',
	 'Europe/Samara' => 'Europe/Samara',
	 'Europe/San_Marino' => 'Europe/San_Marino',
	 'Europe/Sarajevo' => 'Europe/Sarajevo',

	 'Europe/Simferopol' => 'Europe/Simferopol',
	 'Europe/Skopje' => 'Europe/Skopje',
	 'Europe/Sofia' => 'Europe/Sofia',
	 'Europe/Stockholm' => 'Europe/Stockholm',
	 'Europe/Tallinn' => 'Europe/Tallinn',
	 'Europe/Tirane' => 'Europe/Tirane',

	 'Europe/Uzhgorod' => 'Europe/Uzhgorod',
	 'Europe/Vaduz' => 'Europe/Vaduz',
	 'Europe/Vatican' => 'Europe/Vatican',
	 'Europe/Vienna' => 'Europe/Vienna',
	 'Europe/Vilnius' => 'Europe/Vilnius',
	 'Europe/Warsaw' => 'Europe/Warsaw',

	 'Europe/Zagreb' => 'Europe/Zagreb',
	 'Europe/Zaporozhye' => 'Europe/Zaporozhye',
	 'Europe/Zurich' => 'Europe/Zurich',
	 'Indian/Antananarivo' => 'Indian/Antananarivo',
	 'Indian/Chagos' => 'Indian/Chagos',
	 'Indian/Christmas' => 'Indian/Christmas',

	 'Indian/Cocos' => 'Indian/Cocos',
	 'Indian/Comoro' => 'Indian/Comoro',
	 'Indian/Kerguelen' => 'Indian/Kerguelen',
	 'Indian/Mahe' => 'Indian/Mahe',
	 'Indian/Maldives' => 'Indian/Maldives',
	 'Indian/Mauritius' => 'Indian/Mauritius',

	 'Indian/Mayotte' => 'Indian/Mayotte',
	 'Indian/Reunion' => 'Indian/Reunion',
	 'Pacific/Apia' => 'Pacific/Apia',
	 'Pacific/Auckland' => 'Pacific/Auckland',
	 'Pacific/Chatham' => 'Pacific/Chatham',
	 'Pacific/Easter' => 'Pacific/Easter',

	 'Pacific/Efate' => 'Pacific/Efate',
	 'Pacific/Enderbury' => 'Pacific/Enderbury',
	 'Pacific/Fakaofo' => 'Pacific/Fakaofo',
	 'Pacific/Fiji' => 'Pacific/Fiji',
	 'Pacific/Funafuti' => 'Pacific/Funafuti',
	 'Pacific/Galapagos' => 'Pacific/Galapagos',

	 'Pacific/Gambier' => 'Pacific/Gambier',
	 'Pacific/Guadalcanal' => 'Pacific/Guadalcanal',
	 'Pacific/Guam' => 'Pacific/Guam',
	 'Pacific/Honolulu' => 'Pacific/Honolulu',
	 'Pacific/Johnston' => 'Pacific/Johnston',
	 'Pacific/Kiritimati' => 'Pacific/Kiritimati',

	 'Pacific/Kosrae' => 'Pacific/Kosrae',
	 'Pacific/Kwajalein' => 'Pacific/Kwajalein',
	 'Pacific/Majuro' => 'Pacific/Majuro',
	 'Pacific/Marquesas' => 'Pacific/Marquesas',
	 'Pacific/Midway' => 'Pacific/Midway',
	 'Pacific/Nauru' => 'Pacific/Nauru',

	 'Pacific/Niue' => 'Pacific/Niue',
	 'Pacific/Norfolk' => 'Pacific/Norfolk',
	 'Pacific/Noumea' => 'Pacific/Noumea',
	 'Pacific/Pago_Pago' => 'Pacific/Pago_Pago',
	 'Pacific/Palau' => 'Pacific/Palau',
	 'Pacific/Pitcairn' => 'Pacific/Pitcairn',

	 'Pacific/Ponape' => 'Pacific/Ponape',
	 'Pacific/Port_Moresby' => 'Pacific/Port_Moresby',
	 'Pacific/Rarotonga' => 'Pacific/Rarotonga',
	 'Pacific/Saipan' => 'Pacific/Saipan',
	 'Pacific/Tahiti' => 'Pacific/Tahiti',
	 'Pacific/Tarawa' => 'Pacific/Tarawa',

	 'Pacific/Tongatapu' => 'Pacific/Tongatapu',
	 'Pacific/Truk' => 'Pacific/Truk',
	 'Pacific/Wake' => 'Pacific/Wake',
	 'Pacific/Wallis' => 'Pacific/Wallis'
	);
	
	echo form_dropdown('timezone',$tz_options,$selected,$js);
}

/* 
	menu_marker
	Checks if the url class name is the same as the parameter
	if so, returns marked class
*/
function menu_marker($option=FALSE){
	$CI =& get_instance();
	if ($CI->uri->segment(1) == $option){
		return ' class="marked" ';
	}
}

/* 
	thumb_url
	Constructs the thumbnail url using the artwork id 
*/
function thumb_url($artwork_id){
	$CI =& get_instance();
	return base_url().substr($CI->config->config['gallery']['thumb_path'],2).'thumb_'.$artwork_id.'.jpg';
}

/*
	pagination
	Constructs the pagination links
*/
function pagination($total_rows,$per_page,$cur_page,$base_url,$options=array(
											 	'full_tag_open' 	=> '<p>',
											 	'full_tag_close' 	=> '</p>',
											 	'first_link'	  	=> 'First',
												'first_link_tag_open' => '<span>',
												'first_link_tag_close' => '</span>',
												'last_link'			=> 'Last',
												'last_link_tag_open'	=> '<span>',
												'last_link_tag_close'	=> '</span>',
												'next_link'			=> '&gt;',
												'next_tag_open'		=> '<span>',
												'next_tag_close'	=> '</span>',
												'prev_link'		=> '&lt;',
												'prev_tag_open'	=> '<span>',
												'prev_tag_close' => '</span>',
												'cur_tag_open'	=> '<b>',
												'cur_tag_close'	=> '<b>',
												'num_tag_open'		=> '<span>',
												'num_tag_close'		=> '</span>',
												'num_links'			=> 2
											)){
	
	$num_pages = ceil($total_rows/$per_page);
	
	// if item count our per_page is zero no need to continue
	if ($total_rows == 0 OR $per_page == 0)
		return '';
	
	// if there is only one page no need to continue
	if ($num_pages == 1)
		return '';
	
	// if current page number is above the the total number of pages 
	// if so , show last page
	if ($cur_page > $num_pages){
		$cur_page = $num_pages;
	}
		
	// Calculate the start and end numbers. These determine
	// which number to start and end the digit links with
	$start = (($cur_page - $options['num_links']) > 0) ? $cur_page - ($options['num_links'] - 1) : 1;
	$end   = (($cur_page + $options['num_links']) < $num_pages) ? $cur_page + $options['num_links'] : $num_pages;
	
	
	// let the games begin...
	$output = '';
	
	// render the "first" link
	if  ($cur_page > $options['num_links'])
	{
		$output .= $options['first_tag_open'].'<a href="'.$base_url.'?page=1">'.$options['first_link'].'</a>'.$options['first_tag_close'];
	}
	
	// Render the "previous" link
 	if  (($cur_page - $options['num_links']) >= 0)
	{
		$i = $cur_page - 1;
		if ($i == 0) $i = '';
		$output .= $options['prev_tag_open'].'<a href="'.$base_url.'?page='.$i.'">'.$options['prev_link'].'</a>'.$options['prev_tag_close'];
	}
	
	// Write the digit links
	for ($loop = $start; $loop <= $end; $loop++)
	{
		$i = $loop;
				
		if ($i >= 0)
		{
			if ($cur_page == $loop)
			{
				$output .= $options['cur_tag_open'].$loop.$options['cur_tag_close']; // Current page
			}
			else
			{
				$n = ($i == 0) ? '' : $i;
				$output .= $options['num_tag_open'].'<a href="'.$base_url.'?page='.$n.'">'.$loop.'</a>'.$options['num_tag_close'];
			}
		}
	}

	// Render the "next" link
	if ($cur_page < $num_pages)
	{
		$output .= $options['next_tag_open'].'<a href="'.$base_url.'?page='.($cur_page +1).'">'.$options['next_link'].'</a>'.$options['next_tag_close'];
	}

	// Render the "Last" link
	if (($cur_page + $options['num_links']) < $num_pages)
	{
		$i = $num_pages;
		$output .= $options['last_tag_open'].'<a href="'.$base_url.'?page='.$i.'">'.$options['last_link'].'</a>'.$options['last_tag_close'];
	}
	
	// Add the wrapper HTML if exists
	$output = $options['full_tag_open'].$output.$options['full_tag_close'];
	
	return $output;
}

?>
