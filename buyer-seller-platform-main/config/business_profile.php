<?php

return [

    'default_country' => 'IN',

    'countries' => [

        'IN' => [
            'address' => [
                'city_label' => 'City',
                'state_label' => 'State',
                'postal_label' => 'Pincode',
                'country_label' => 'Country',
                'google_restrict_country' => 'in',
                'restrict_google_country' => true,
            ],
            'tax_fields' => [
                ['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'GST'],
                ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'GST Number'],
                ['key' => 'tax_aadhaar', 'label' => 'Aadhaar Number', 'type' => 'text', 'placeholder' => 'Aadhaar Number'],
                ['key' => 'tax_pan', 'label' => 'PAN Number', 'type' => 'text', 'placeholder' => 'PAN Number'],
                ['key' => 'tax_iec', 'label' => 'IEC Number', 'type' => 'text', 'placeholder' => 'IEC Number'],
                ['key' => 'tax_tan', 'label' => 'TAN Number', 'type' => 'text', 'placeholder' => 'TAN Number'],
                ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'],
            ],
            'document_fields' => [
                ['key' => 'gst', 'label' => 'Upload GST Document'],
                ['key' => 'aadhaar', 'label' => 'Upload Aadhaar Document'],
                ['key' => 'pan', 'label' => 'Upload PAN Document'],
                ['key' => 'iec', 'label' => 'Upload IEC Document'],
            ],
        ],

        'AE' => [
            'address' => [
                'city_label' => 'City',
                'state_label' => 'Emirate / State',
                'postal_label' => 'Postal Code',
                'country_label' => 'Country',
                'google_restrict_country' => 'ae',
                'restrict_google_country' => true,
            ],
            'tax_fields' => [
                ['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'Trade License / VAT'],
                ['key' => 'tax_id_number', 'label' => 'Trade License Number', 'type' => 'text', 'placeholder' => 'Trade License Number'],
                ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'],
                ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number'],
            ],
            'document_fields' => [
                ['key' => 'gst', 'label' => 'Upload Trade License'],
                ['key' => 'pan', 'label' => 'Upload Company Registration Certificate'],
                ['key' => 'iec', 'label' => 'Upload VAT Certificate'],
            ],
        ],

        'CN' => [
            'address' => [
                'city_label' => 'City',
                'state_label' => 'Province / Region',
                'postal_label' => 'Postal Code',
                'country_label' => 'Country',
                'google_restrict_country' => 'cn',
                'restrict_google_country' => true,
            ],
            'tax_fields' => [
                ['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'Business License / Tax Registration'],
                ['key' => 'tax_id_number', 'label' => 'Unified Social Credit Code', 'type' => 'text', 'placeholder' => 'Unified Social Credit Code'],
                ['key' => 'tax_vat', 'label' => 'Tax Registration Number', 'type' => 'text', 'placeholder' => 'Tax Registration Number'],
                ['key' => 'tax_pan', 'label' => 'Export Registration Number', 'type' => 'text', 'placeholder' => 'Export Registration Number'],
            ],
            'document_fields' => [
                ['key' => 'gst', 'label' => 'Upload Business License'],
                ['key' => 'pan', 'label' => 'Upload Export Registration Certificate'],
                ['key' => 'iec', 'label' => 'Upload Tax Registration Certificate'],
            ],
        ],

        'EG' => ['address' => ['city_label' => 'City', 'state_label' => 'Governorate', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'eg', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'ET' => ['address' => ['city_label' => 'City', 'state_label' => 'Region / State', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'et', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'ZA' => ['address' => ['city_label' => 'City', 'state_label' => 'Province', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'za', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'MX' => ['address' => ['city_label' => 'City', 'state_label' => 'State', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'mx', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'VE' => ['address' => ['city_label' => 'City', 'state_label' => 'State', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 've', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'CO' => ['address' => ['city_label' => 'City', 'state_label' => 'Department / State', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'co', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'BR' => ['address' => ['city_label' => 'City', 'state_label' => 'State', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'br', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'AR' => ['address' => ['city_label' => 'City', 'state_label' => 'Province', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'ar', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'PE' => ['address' => ['city_label' => 'City', 'state_label' => 'Region', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'pe', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'CL' => ['address' => ['city_label' => 'City', 'state_label' => 'Region', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'cl', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'EC' => ['address' => ['city_label' => 'City', 'state_label' => 'Province', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'ec', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'PY' => ['address' => ['city_label' => 'City', 'state_label' => 'Department', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'py', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'KR' => ['address' => ['city_label' => 'City', 'state_label' => 'Province / City', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'kr', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'JP' => ['address' => ['city_label' => 'City', 'state_label' => 'Prefecture', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'jp', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],
        'TW' => ['address' => ['city_label' => 'City', 'state_label' => 'County / City', 'postal_label' => 'Postal Code', 'country_label' => 'Country', 'google_restrict_country' => 'tw', 'restrict_google_country' => true], 'tax_fields' => [['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'], ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'], ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'], ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number']], 'document_fields' => [['key' => 'gst', 'label' => 'Upload Tax Registration Document'], ['key' => 'pan', 'label' => 'Upload Company Registration Certificate']]],

        'DEFAULT_INTL' => [
            'address' => [
                'city_label' => 'City',
                'state_label' => 'State / Region',
                'postal_label' => 'Postal Code',
                'country_label' => 'Country',
                'google_restrict_country' => null,
                'restrict_google_country' => false,
            ],
            'tax_fields' => [
                ['key' => 'tax_id_type', 'label' => 'Tax ID Type', 'type' => 'text', 'placeholder' => 'VAT / Registration'],
                ['key' => 'tax_id_number', 'label' => 'Tax ID Number', 'type' => 'text', 'placeholder' => 'Tax ID Number'],
                ['key' => 'tax_vat', 'label' => 'VAT Number', 'type' => 'text', 'placeholder' => 'VAT Number'],
                ['key' => 'tax_pan', 'label' => 'Company Registration Number', 'type' => 'text', 'placeholder' => 'Company Registration Number'],
            ],
            'document_fields' => [
                ['key' => 'gst', 'label' => 'Upload Tax Registration Document'],
                ['key' => 'pan', 'label' => 'Upload Company Registration Certificate'],
            ],
        ],

    ],

];
