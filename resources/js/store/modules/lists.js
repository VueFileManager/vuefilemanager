const defaultState = {
    s3Regions: [
        {
            label: 'us-east-1',
            value: 'us-east-1',
        },
        {
            label: 'us-east-2',
            value: 'us-east-2',
        },
        {
            label: 'us-west-1',
            value: 'us-west-1',
        },
        {
            label: 'us-west-2',
            value: 'us-west-2',
        },
        {
            label: 'af-south-1',
            value: 'af-south-1',
        },
        {
            label: 'ap-east-1',
            value: 'ap-east-1',
        },
        {
            label: 'ap-south-1',
            value: 'ap-south-1',
        },
        {
            label: 'ap-southeast-1',
            value: 'ap-southeast-1',
        },
        {
            label: 'ap-southeast-2',
            value: 'ap-southeast-2',
        },
        {
            label: 'ap-southeast-3',
            value: 'ap-southeast-3',
        },
        {
            label: 'ap-northeast-1',
            value: 'ap-northeast-1',
        },
        {
            label: 'ap-northeast-2',
            value: 'ap-northeast-2',
        },
        {
            label: 'ap-northeast-3',
            value: 'ap-northeast-3',
        },
        {
            label: 'ca-central-1',
            value: 'ca-central-1',
        },
        {
            label: 'eu-central-1',
            value: 'eu-central-1',
        },
        {
            label: 'eu-west-1',
            value: 'eu-west-1',
        },
        {
            label: 'eu-west-2',
            value: 'eu-west-2',
        },
        {
            label: 'eu-south-1',
            value: 'eu-south-1',
        },
        {
            label: 'eu-west-3',
            value: 'eu-west-3',
        },
        {
            label: 'eu-north-1',
            value: 'eu-north-1',
        },
        {
            label: 'me-south-1',
            value: 'me-south-1',
        },
        {
            label: 'sa-east-1',
            value: 'sa-east-1',
        },
    ],
    transactionColumns: [
        {
            label: 'note',
            field: 'note',
            sortable: true,
        },
        {
            label: 'user',
            field: 'user_id',
            sortable: true,
        },
        {
            label: 'status',
            field: 'status',
            sortable: true,
        },
        {
            label: 'type',
            field: 'type',
            sortable: true,
        },
        {
            label: 'total',
            field: 'amount',
            sortable: true,
        },
        {
            label: 'payed_at',
            field: 'created_at',
            sortable: true,
        },
        {
            label: 'service',
            field: 'driver',
            sortable: true,
        },
        {
            label: 'actions',
            field: 'actions',
            sortable: false,
        },
    ],
    roles: [
        {
            label: 'roles.admin',
            value: 'admin',
        },
        {
            label: 'roles.user',
            value: 'user',
        },
    ],
    subscriptionTypes: [
        {
            label: 'Metered',
            value: 'metered',
        },
        {
            label: 'Fixed',
            value: 'fixed',
        },
        {
            label: 'None',
            value: 'none',
        },
    ],
    teamPermissions: {
        'can-edit': 'can_edit',
        'can-view': 'can_view',
    },
    countries: [
        { label: 'Czech Republic', value: 'CZ' },
        { label: 'Slovakia', value: 'SK' },
        { label: 'Afghanistan', value: 'AF' },
        { label: 'Åland Islands', value: 'AX' },
        { label: 'Albania', value: 'AL' },
        { label: 'Algeria', value: 'DZ' },
        { label: 'American Samoa', value: 'AS' },
        { label: 'AndorrA', value: 'AD' },
        { label: 'Angola', value: 'AO' },
        { label: 'Anguilla', value: 'AI' },
        { label: 'Antarctica', value: 'AQ' },
        { label: 'Antigua and Barbuda', value: 'AG' },
        { label: 'Argentina', value: 'AR' },
        { label: 'Armenia', value: 'AM' },
        { label: 'Aruba', value: 'AW' },
        { label: 'Australia', value: 'AU' },
        { label: 'Austria', value: 'AT' },
        { label: 'Azerbaijan', value: 'AZ' },
        { label: 'Bahamas', value: 'BS' },
        { label: 'Bahrain', value: 'BH' },
        { label: 'Bangladesh', value: 'BD' },
        { label: 'Barbados', value: 'BB' },
        { label: 'Belarus', value: 'BY' },
        { label: 'Belgium', value: 'BE' },
        { label: 'Belize', value: 'BZ' },
        { label: 'Benin', value: 'BJ' },
        { label: 'Bermuda', value: 'BM' },
        { label: 'Bhutan', value: 'BT' },
        { label: 'Bolivia', value: 'BO' },
        { label: 'Bosnia and Herzegovina', value: 'BA' },
        { label: 'Botswana', value: 'BW' },
        { label: 'Bouvet Island', value: 'BV' },
        { label: 'Brazil', value: 'BR' },
        { label: 'British Indian Ocean Territory', value: 'IO' },
        { label: 'Brunei Darussalam', value: 'BN' },
        { label: 'Bulgaria', value: 'BG' },
        { label: 'Burkina Faso', value: 'BF' },
        { label: 'Burundi', value: 'BI' },
        { label: 'Cambodia', value: 'KH' },
        { label: 'Cameroon', value: 'CM' },
        { label: 'Canada', value: 'CA' },
        { label: 'Cape Verde', value: 'CV' },
        { label: 'Cayman Islands', value: 'KY' },
        { label: 'Central African Republic', value: 'CF' },
        { label: 'Chad', value: 'TD' },
        { label: 'Chile', value: 'CL' },
        { label: 'China', value: 'CN' },
        { label: 'Christmas Island', value: 'CX' },
        { label: 'Cocos (Keeling) Islands', value: 'CC' },
        { label: 'Colombia', value: 'CO' },
        { label: 'Comoros', value: 'KM' },
        { label: 'Congo', value: 'CG' },
        { label: 'Congo, The Democratic Republic of the', value: 'CD' },
        { label: 'Cook Islands', value: 'CK' },
        { label: 'Costa Rica', value: 'CR' },
        { label: "Cote D'Ivoire", value: 'CI' },
        { label: 'Croatia', value: 'HR' },
        { label: 'Cuba', value: 'CU' },
        { label: 'Cyprus', value: 'CY' },
        { label: 'Denmark', value: 'DK' },
        { label: 'Djibouti', value: 'DJ' },
        { label: 'Dominica', value: 'DM' },
        { label: 'Dominican Republic', value: 'DO' },
        { label: 'Ecuador', value: 'EC' },
        { label: 'Egypt', value: 'EG' },
        { label: 'El Salvador', value: 'SV' },
        { label: 'Equatorial Guinea', value: 'GQ' },
        { label: 'Eritrea', value: 'ER' },
        { label: 'Estonia', value: 'EE' },
        { label: 'Ethiopia', value: 'ET' },
        { label: 'Falkland Islands (Malvinas)', value: 'FK' },
        { label: 'Faroe Islands', value: 'FO' },
        { label: 'Fiji', value: 'FJ' },
        { label: 'Finland', value: 'FI' },
        { label: 'France', value: 'FR' },
        { label: 'French Guiana', value: 'GF' },
        { label: 'French Polynesia', value: 'PF' },
        { label: 'French Southern Territories', value: 'TF' },
        { label: 'Gabon', value: 'GA' },
        { label: 'Gambia', value: 'GM' },
        { label: 'Georgia', value: 'GE' },
        { label: 'Germany', value: 'DE' },
        { label: 'Ghana', value: 'GH' },
        { label: 'Gibraltar', value: 'GI' },
        { label: 'Greece', value: 'GR' },
        { label: 'Greenland', value: 'GL' },
        { label: 'Grenada', value: 'GD' },
        { label: 'Guadeloupe', value: 'GP' },
        { label: 'Guam', value: 'GU' },
        { label: 'Guatemala', value: 'GT' },
        { label: 'Guernsey', value: 'GG' },
        { label: 'Guinea', value: 'GN' },
        { label: 'Guinea-Bissau', value: 'GW' },
        { label: 'Guyana', value: 'GY' },
        { label: 'Haiti', value: 'HT' },
        { label: 'Heard Island and Mcdonald Islands', value: 'HM' },
        { label: 'Holy See (Vatican City State)', value: 'VA' },
        { label: 'Honduras', value: 'HN' },
        { label: 'Hong Kong', value: 'HK' },
        { label: 'Hungary', value: 'HU' },
        { label: 'Iceland', value: 'IS' },
        { label: 'India', value: 'IN' },
        { label: 'Indonesia', value: 'ID' },
        { label: 'Iran, Islamic Republic Of', value: 'IR' },
        { label: 'Iraq', value: 'IQ' },
        { label: 'Ireland', value: 'IE' },
        { label: 'Isle of Man', value: 'IM' },
        { label: 'Israel', value: 'IL' },
        { label: 'Italy', value: 'IT' },
        { label: 'Jamaica', value: 'JM' },
        { label: 'Japan', value: 'JP' },
        { label: 'Jersey', value: 'JE' },
        { label: 'Jordan', value: 'JO' },
        { label: 'Kazakhstan', value: 'KZ' },
        { label: 'Kenya', value: 'KE' },
        { label: 'Kiribati', value: 'KI' },
        { label: "Korea, Democratic People'S Republic of", value: 'KP' },
        { label: 'Korea, Republic of', value: 'KR' },
        { label: 'Kuwait', value: 'KW' },
        { label: 'Kyrgyzstan', value: 'KG' },
        { label: "Lao People'S Democratic Republic", value: 'LA' },
        { label: 'Latvia', value: 'LV' },
        { label: 'Lebanon', value: 'LB' },
        { label: 'Lesotho', value: 'LS' },
        { label: 'Liberia', value: 'LR' },
        { label: 'Libyan Arab Jamahiriya', value: 'LY' },
        { label: 'Liechtenstein', value: 'LI' },
        { label: 'Lithuania', value: 'LT' },
        { label: 'Luxembourg', value: 'LU' },
        { label: 'Macao', value: 'MO' },
        { label: 'Macedonia, The Former Yugoslav Republic of', value: 'MK' },
        { label: 'Madagascar', value: 'MG' },
        { label: 'Malawi', value: 'MW' },
        { label: 'Malaysia', value: 'MY' },
        { label: 'Maldives', value: 'MV' },
        { label: 'Mali', value: 'ML' },
        { label: 'Malta', value: 'MT' },
        { label: 'Marshall Islands', value: 'MH' },
        { label: 'Martinique', value: 'MQ' },
        { label: 'Mauritania', value: 'MR' },
        { label: 'Mauritius', value: 'MU' },
        { label: 'Mayotte', value: 'YT' },
        { label: 'Mexico', value: 'MX' },
        { label: 'Micronesia, Federated States of', value: 'FM' },
        { label: 'Moldova, Republic of', value: 'MD' },
        { label: 'Monaco', value: 'MC' },
        { label: 'Mongolia', value: 'MN' },
        { label: 'Montserrat', value: 'MS' },
        { label: 'Morocco', value: 'MA' },
        { label: 'Mozambique', value: 'MZ' },
        { label: 'Myanmar', value: 'MM' },
        { label: 'Namibia', value: 'NA' },
        { label: 'Nauru', value: 'NR' },
        { label: 'Nepal', value: 'NP' },
        { label: 'Netherlands', value: 'NL' },
        { label: 'Netherlands Antilles', value: 'AN' },
        { label: 'New Caledonia', value: 'NC' },
        { label: 'New Zealand', value: 'NZ' },
        { label: 'Nicaragua', value: 'NI' },
        { label: 'Niger', value: 'NE' },
        { label: 'Nigeria', value: 'NG' },
        { label: 'Niue', value: 'NU' },
        { label: 'Norfolk Island', value: 'NF' },
        { label: 'Northern Mariana Islands', value: 'MP' },
        { label: 'Norway', value: 'NO' },
        { label: 'Oman', value: 'OM' },
        { label: 'Pakistan', value: 'PK' },
        { label: 'Palau', value: 'PW' },
        { label: 'Palestinian Territory, Occupied', value: 'PS' },
        { label: 'Panama', value: 'PA' },
        { label: 'Papua New Guinea', value: 'PG' },
        { label: 'Paraguay', value: 'PY' },
        { label: 'Peru', value: 'PE' },
        { label: 'Philippines', value: 'PH' },
        { label: 'Pitcairn', value: 'PN' },
        { label: 'Poland', value: 'PL' },
        { label: 'Portugal', value: 'PT' },
        { label: 'Puerto Rico', value: 'PR' },
        { label: 'Qatar', value: 'QA' },
        { label: 'Reunion', value: 'RE' },
        { label: 'Romania', value: 'RO' },
        { label: 'Russian Federation', value: 'RU' },
        { label: 'RWANDA', value: 'RW' },
        { label: 'Saint Helena', value: 'SH' },
        { label: 'Saint Kitts and Nevis', value: 'KN' },
        { label: 'Saint Lucia', value: 'LC' },
        { label: 'Saint Pierre and Miquelon', value: 'PM' },
        { label: 'Saint Vincent and the Grenadines', value: 'VC' },
        { label: 'Samoa', value: 'WS' },
        { label: 'San Marino', value: 'SM' },
        { label: 'Sao Tome and Principe', value: 'ST' },
        { label: 'Saudi Arabia', value: 'SA' },
        { label: 'Senegal', value: 'SN' },
        { label: 'Serbia and Montenegro', value: 'CS' },
        { label: 'Seychelles', value: 'SC' },
        { label: 'Sierra Leone', value: 'SL' },
        { label: 'Singapore', value: 'SG' },
        { label: 'Slovenia', value: 'SI' },
        { label: 'Solomon Islands', value: 'SB' },
        { label: 'Somalia', value: 'SO' },
        { label: 'South Africa', value: 'ZA' },
        { label: 'South Georgia and the South Sandwich Islands', value: 'GS' },
        { label: 'Spain', value: 'ES' },
        { label: 'Sri Lanka', value: 'LK' },
        { label: 'Sudan', value: 'SD' },
        { label: 'Suriname', value: 'SR' },
        { label: 'Svalbard and Jan Mayen', value: 'SJ' },
        { label: 'Swaziland', value: 'SZ' },
        { label: 'Sweden', value: 'SE' },
        { label: 'Switzerland', value: 'CH' },
        { label: 'Syrian Arab Republic', value: 'SY' },
        { label: 'Taiwan, Province of China', value: 'TW' },
        { label: 'Tajikistan', value: 'TJ' },
        { label: 'Tanzania, United Republic of', value: 'TZ' },
        { label: 'Thailand', value: 'TH' },
        { label: 'Timor-Leste', value: 'TL' },
        { label: 'Togo', value: 'TG' },
        { label: 'Tokelau', value: 'TK' },
        { label: 'Tonga', value: 'TO' },
        { label: 'Trinidad and Tobago', value: 'TT' },
        { label: 'Tunisia', value: 'TN' },
        { label: 'Turkey', value: 'TR' },
        { label: 'Turkmenistan', value: 'TM' },
        { label: 'Turks and Caicos Islands', value: 'TC' },
        { label: 'Tuvalu', value: 'TV' },
        { label: 'Uganda', value: 'UG' },
        { label: 'Ukraine', value: 'UA' },
        { label: 'United Arab Emirates', value: 'AE' },
        { label: 'United Kingdom', value: 'GB' },
        { label: 'United States', value: 'US' },
        { label: 'United States Minor Outlying Islands', value: 'UM' },
        { label: 'Uruguay', value: 'UY' },
        { label: 'Uzbekistan', value: 'UZ' },
        { label: 'Vanuatu', value: 'VU' },
        { label: 'Venezuela', value: 'VE' },
        { label: 'Viet Nam', value: 'VN' },
        { label: 'Virgin Islands, British', value: 'VG' },
        { label: 'Virgin Islands, U.S.', value: 'VI' },
        { label: 'Wallis and Futuna', value: 'WF' },
        { label: 'Western Sahara', value: 'EH' },
        { label: 'Yemen', value: 'YE' },
        { label: 'Zambia', value: 'ZM' },
        { label: 'Zimbabwe', value: 'ZW' },
    ],
    expirationList: [
        {
            label: ['shared_form.expiration_hour', { value: 1 }],
            value: 1,
        },
        {
            label: ['shared_form.expiration_hour', { value: 2 }],
            value: 2,
        },
        {
            label: ['shared_form.expiration_hour', { value: 6 }],
            value: 6,
        },
        {
            label: ['shared_form.expiration_hour', { value: 12 }],
            value: 12,
        },
        {
            label: ['shared_form.expiration_day', { value: 1 }],
            value: 24,
        },
        {
            label: ['shared_form.expiration_day', { value: 2 }],
            value: 48,
        },
        {
            label: ['shared_form.expiration_day', { value: 7 }],
            value: 168,
        },
    ],
    intervalList: [
        {
            label: 'monthly',
            value: 'month',
        },
        {
            label: 'yearly',
            value: 'year',
        },
    ],
    currencyList: [
        {
            label: 'USD - United States Dollar',
            value: 'USD',
        },
        {
            label: 'EUR - Euro',
            value: 'EUR',
        },
        {
            label: 'GBP - British Pound',
            value: 'GBP',
        },
        {
            label: 'AFN - Afghan Afghani',
            value: 'AFN',
        },
        {
            label: 'ALL - Albanian Lek',
            value: 'ALL',
        },
        {
            label: 'DZD - Algerian Dinar',
            value: 'DZD',
        },
        {
            label: 'AOA - Angolan Kwanza',
            value: 'AOA',
        },
        {
            label: 'ARS - Argentine Peso',
            value: 'ARS',
        },
        {
            label: 'AMD - Armenian Dram',
            value: 'AMD',
        },
        {
            label: 'AWG - Aruban Florin',
            value: 'AWG',
        },
        {
            label: 'AUD - Australian Dollar',
            value: 'AUD',
        },
        {
            label: 'AZN - Azerbaijani Manat',
            value: 'AZN',
        },
        {
            label: 'BDT - Bangladeshi Taka',
            value: 'BDT',
        },
        {
            label: 'BBD - Barbadian Dollar',
            value: 'BBD',
        },
        {
            label: 'BZD - Belize Dollar',
            value: 'BZD',
        },
        {
            label: 'BMD - Bermudian Dollar',
            value: 'BMD',
        },
        {
            label: 'BOB - Bolivian Boliviano',
            value: 'BOB',
        },
        {
            label: 'BAM - Bosnia & Herzegovina Convertible Mark',
            value: 'BAM',
        },
        {
            label: 'BWP - Botswana Pula',
            value: 'BWP',
        },
        {
            label: 'BRL - Brazilian Real',
            value: 'BRL',
        },
        {
            label: 'BND - Brunei Dollar',
            value: 'BND',
        },
        {
            label: 'BGN - Bulgarian Lev',
            value: 'BGN',
        },
        {
            label: 'BIF - Burundian Franc',
            value: 'BIF',
        },
        {
            label: 'KHR - Cambodian Riel',
            value: 'KHR',
        },
        {
            label: 'CAD - Canadian Dollar',
            value: 'CAD',
        },
        {
            label: 'CVE - Cape Verdean Escudo',
            value: 'CVE',
        },
        {
            label: 'KYD - Cayman Islands Dollar',
            value: 'KYD',
        },
        {
            label: 'XAF - Central African Cfa Franc',
            value: 'XAF',
        },
        {
            label: 'XPF - Cfp Franc',
            value: 'XPF',
        },
        {
            label: 'CLP - Chilean Peso',
            value: 'CLP',
        },
        {
            label: 'CNY - Chinese Renminbi Yuan',
            value: 'CNY',
        },
        {
            label: 'COP - Colombian Peso',
            value: 'COP',
        },
        {
            label: 'KMF - Comorian Franc',
            value: 'KMF',
        },
        {
            label: 'CDF - Congolese Franc',
            value: 'CDF',
        },
        {
            label: 'CRC - Costa Rican Colón',
            value: 'CRC',
        },
        {
            label: 'HRK - Croatian Kuna',
            value: 'HRK',
        },
        {
            label: 'CZK - Czech Koruna',
            value: 'CZK',
        },
        {
            label: 'DKK - Danish Krone',
            value: 'DKK',
        },
        {
            label: 'DJF - Djiboutian Franc',
            value: 'DJF',
        },
        {
            label: 'DOP - Dominican Peso',
            value: 'DOP',
        },
        {
            label: 'XCD - East Caribbean Dollar',
            value: 'XCD',
        },
        {
            label: 'EGP - Egyptian Pound',
            value: 'EGP',
        },
        {
            label: 'ETB - Ethiopian Birr',
            value: 'ETB',
        },
        {
            label: 'FKP - Falkland Islands Pound',
            value: 'FKP',
        },
        {
            label: 'FJD - Fijian Dollar',
            value: 'FJD',
        },
        {
            label: 'GMD - Gambian Dalasi',
            value: 'GMD',
        },
        {
            label: 'GEL - Georgian Lari',
            value: 'GEL',
        },
        {
            label: 'GIP - Gibraltar Pound',
            value: 'GIP',
        },
        {
            label: 'GTQ - Guatemalan Quetzal',
            value: 'GTQ',
        },
        {
            label: 'GNF - Guinean Franc',
            value: 'GNF',
        },
        {
            label: 'GYD - Guyanese Dollar',
            value: 'GYD',
        },
        {
            label: 'HTG - Haitian Gourde',
            value: 'HTG',
        },
        {
            label: 'HNL - Honduran Lempira',
            value: 'HNL',
        },
        {
            label: 'HKD - Hong Kong Dollar',
            value: 'HKD',
        },
        {
            label: 'HUF - Hungarian Forint',
            value: 'HUF',
        },
        {
            label: 'ISK - Icelandic Króna',
            value: 'ISK',
        },
        {
            label: 'INR - Indian Rupee',
            value: 'INR',
        },
        {
            label: 'IDR - Indonesian Rupiah',
            value: 'IDR',
        },
        {
            label: 'ILS - Israeli New Sheqel',
            value: 'ILS',
        },
        {
            label: 'JMD - Jamaican Dollar',
            value: 'JMD',
        },
        {
            label: 'JPY - Japanese Yen',
            value: 'JPY',
        },
        {
            label: 'KZT - Kazakhstani Tenge',
            value: 'KZT',
        },
        {
            label: 'KES - Kenyan Shilling',
            value: 'KES',
        },
        {
            label: 'KGS - Kyrgyzstani Som',
            value: 'KGS',
        },
        {
            label: 'LAK - Lao Kip',
            value: 'LAK',
        },
        {
            label: 'LBP - Lebanese Pound',
            value: 'LBP',
        },
        {
            label: 'LSL - Lesotho Loti',
            value: 'LSL',
        },
        {
            label: 'LRD - Liberian Dollar',
            value: 'LRD',
        },
        {
            label: 'MOP - Macanese Pataca',
            value: 'MOP',
        },
        {
            label: 'MKD - Macedonian Denar',
            value: 'MKD',
        },
        {
            label: 'MGA - Malagasy Ariary',
            value: 'MGA',
        },
        {
            label: 'MWK - Malawian Kwacha',
            value: 'MWK',
        },
        {
            label: 'MYR - Malaysian Ringgit',
            value: 'MYR',
        },
        {
            label: 'MVR - Maldivian Rufiyaa',
            value: 'MVR',
        },
        {
            label: 'MRO - Mauritanian Ouguiya',
            value: 'MRO',
        },
        {
            label: 'MUR - Mauritian Rupee',
            value: 'MUR',
        },
        {
            label: 'MXN - Mexican Peso',
            value: 'MXN',
        },
        {
            label: 'MDL - Moldovan Leu',
            value: 'MDL',
        },
        {
            label: 'MNT - Mongolian Tögrög',
            value: 'MNT',
        },
        {
            label: 'MAD - Moroccan Dirham',
            value: 'MAD',
        },
        {
            label: 'MZN - Mozambican Metical',
            value: 'MZN',
        },
        {
            label: 'MMK - Myanmar Kyat',
            value: 'MMK',
        },
        {
            label: 'NAD - Namibian Dollar',
            value: 'NAD',
        },
        {
            label: 'NPR - Nepalese Rupee',
            value: 'NPR',
        },
        {
            label: 'ANG - Netherlands Antillean Gulden',
            value: 'ANG',
        },
        {
            label: 'TWD - New Taiwan Dollar',
            value: 'TWD',
        },
        {
            label: 'NZD - New Zealand Dollar',
            value: 'NZD',
        },
        {
            label: 'NIO - Nicaraguan Córdoba',
            value: 'NIO',
        },
        {
            label: 'NGN - Nigerian Naira',
            value: 'NGN',
        },
        {
            label: 'NOK - Norwegian Krone',
            value: 'NOK',
        },
        {
            label: 'PKR - Pakistani Rupee',
            value: 'PKR',
        },
        {
            label: 'PAB - Panamanian Balboa',
            value: 'PAB',
        },
        {
            label: 'PGK - Papua New Guinean Kina',
            value: 'PGK',
        },
        {
            label: 'PYG - Paraguayan Guaraní',
            value: 'PYG',
        },
        {
            label: 'PEN - Peruvian Nuevo Sol',
            value: 'PEN',
        },
        {
            label: 'PHP - Philippine Peso',
            value: 'PHP',
        },
        {
            label: 'PLN - Polish Złoty',
            value: 'PLN',
        },
        {
            label: 'QAR - Qatari Riyal',
            value: 'QAR',
        },
        {
            label: 'RON - Romanian Leu',
            value: 'RON',
        },
        {
            label: 'RUB - Russian Ruble',
            value: 'RUB',
        },
        {
            label: 'RWF - Rwandan Franc',
            value: 'RWF',
        },
        {
            label: 'STD - São Tomé and Príncipe Dobra',
            value: 'STD',
        },
        {
            label: 'SHP - Saint Helenian Pound',
            value: 'SHP',
        },
        {
            label: 'SVC - Salvadoran Colón',
            value: 'SVC',
        },
        {
            label: 'WST - Samoan Tala',
            value: 'WST',
        },
        {
            label: 'SAR - Saudi Riyal',
            value: 'SAR',
        },
        {
            label: 'RSD - Serbian Dinar',
            value: 'RSD',
        },
        {
            label: 'SCR - Seychellois Rupee',
            value: 'SCR',
        },
        {
            label: 'SLL - Sierra Leonean Leone',
            value: 'SLL',
        },
        {
            label: 'SGD - Singapore Dollar',
            value: 'SGD',
        },
        {
            label: 'SBD - Solomon Islands Dollar',
            value: 'SBD',
        },
        {
            label: 'SOS - Somali Shilling',
            value: 'SOS',
        },
        {
            label: 'ZAR - South African Rand',
            value: 'ZAR',
        },
        {
            label: 'KRW - South Korean Won',
            value: 'KRW',
        },
        {
            label: 'LKR - Sri Lankan Rupee',
            value: 'LKR',
        },
        {
            label: 'SRD - Surinamese Dollar',
            value: 'SRD',
        },
        {
            label: 'SZL - Swazi Lilangeni',
            value: 'SZL',
        },
        {
            label: 'SEK - Swedish Krona',
            value: 'SEK',
        },
        {
            label: 'CHF - Swiss Franc',
            value: 'CHF',
        },
        {
            label: 'TJS - Tajikistani Somoni',
            value: 'TJS',
        },
        {
            label: 'TZS - Tanzanian Shilling',
            value: 'TZS',
        },
        {
            label: 'THB - Thai Baht',
            value: 'THB',
        },
        {
            label: 'TOP - Tongan Paʻanga',
            value: 'TOP',
        },
        {
            label: 'TTD - Trinidad and Tobago Dollar',
            value: 'TTD',
        },
        {
            label: 'TRY - Turkish Lira',
            value: 'TRY',
        },
        {
            label: 'UGX - Ugandan Shilling',
            value: 'UGX',
        },
        {
            label: 'UAH - Ukrainian Hryvnia',
            value: 'UAH',
        },
        {
            label: 'AED - United Arab Emirates Dirham',
            value: 'AED',
        },
        {
            label: 'UYU - Uruguayan Peso',
            value: 'UYU',
        },
        {
            label: 'UZS - Uzbekistani Som',
            value: 'UZS',
        },
        {
            label: 'VUV - Vanuatu Vatu',
            value: 'VUV',
        },
        {
            label: 'VND - Vietnamese Đồng',
            value: 'VND',
        },
        {
            label: 'XOF - West African Cfa Franc',
            value: 'XOF',
        },
        {
            label: 'YER - Yemeni Rial',
            value: 'YER',
        },
        {
            label: 'ZMW - Zambian Kwacha',
            value: 'ZMW',
        },
    ],
    timezones: [
        {
            value: '-12.0',
            label: '(GMT -12:00) Eniwetok, Kwajalein',
        },
        {
            value: '-11.0',
            label: '(GMT -11:00) Midway Island, Samoa',
        },
        {
            value: '-10.0',
            label: '(GMT -10:00) Hawaii',
        },
        {
            value: '-9.0',
            label: '(GMT -9:00) Alaska',
        },
        {
            value: '-8.0',
            label: '(GMT -8:00) Pacific Time (US & Canada)',
        },
        {
            value: '-7.0',
            label: '(GMT -7:00) Mountain Time (US & Canada)',
        },
        {
            value: '-6.0',
            label: '(GMT -6:00) Central Time (US & Canada), Mexico City',
        },
        {
            value: '-5.0',
            label: '(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima',
        },
        {
            value: '-4.0',
            label: '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
        },
        {
            value: '-3.5',
            label: '(GMT -3:30) Newfoundland',
        },
        {
            value: '-3.0',
            label: '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
        },
        {
            value: '-2.0',
            label: '(GMT -2:00) Mid-Atlantic',
        },
        {
            value: '-1.0',
            label: '(GMT -1:00) Azores, Cape Verde Islands',
        },
        {
            value: '0.0',
            label: '(GMT) Western Europe Time, London, Lisbon, Casablanca',
        },
        {
            value: '1.0',
            label: '(GMT +1:00) Brussels, Copenhagen, Madrid, Paris',
        },
        {
            value: '2.0',
            label: '(GMT +2:00) Kaliningrad, South Africa',
        },
        {
            value: '3.0',
            label: '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
        },
        {
            value: '3.5',
            label: '(GMT +3:30) Tehran',
        },
        {
            value: '4.0',
            label: '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
        },
        {
            value: '4.5',
            label: '(GMT +4:30) Kabul',
        },
        {
            value: '5.0',
            label: '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
        },
        {
            value: '5.5',
            label: '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi',
        },
        {
            value: '5.75',
            label: '(GMT +5:45) Kathmandu',
        },
        {
            value: '6.0',
            label: '(GMT +6:00) Almaty, Dhaka, Colombo',
        },
        {
            value: '7.0',
            label: '(GMT +7:00) Bangkok, Hanoi, Jakarta',
        },
        {
            value: '8.0',
            label: '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
        },
        {
            value: '9.0',
            label: '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
        },
        {
            value: '9.5',
            label: '(GMT +9:30) Adelaide, Darwin',
        },
        {
            value: '10.0',
            label: '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
        },
        {
            value: '11.0',
            label: '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
        },
        {
            value: '12.0',
            label: '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka',
        },
    ],
}

const getters = {
    transactionColumns: (state) => state.transactionColumns,
    subscriptionTypes: (state) => state.subscriptionTypes,
    teamPermissions: (state) => state.teamPermissions,
    expirationList: (state) => state.expirationList,
    currencyList: (state) => state.currencyList,
    intervalList: (state) => state.intervalList,
    timezones: (state) => state.timezones,
    countries: (state) => state.countries,
    s3Regions: (state) => state.s3Regions,
    roles: (state) => state.roles,
}

export default {
    state: defaultState,
    getters,
}
