<?php

return [

    'default_country' => 'IN',

    'countries' => [

        'IN' => [
            'dial_code' => '+91',
            'postal_label' => 'Pincode',
            'turnover_units' => ['Lakh', 'Crore', 'Million', 'Billion'],
            'bank' => [
                'account_type_label' => 'Account Type',
                'account_type_options' => ['Savings', 'Current'],
                'account_holder_label' => 'Account Holder Name',
                'account_number_label' => 'Account Number',
                'account_number_confirm_label' => 'Confirm Account Number',
                'bank_code_label' => 'IFSC Code',
                'linked_mobile_label' => 'Number Linked',
                'bank_code_name' => 'ifsc_code',
                'bank_code_placeholder' => 'IFSC Code',
            ],
        ],

        'US' => [
            'dial_code' => '+1',
            'postal_label' => 'ZIP Code',
            'turnover_units' => ['Thousand', 'Million', 'Billion'],
            'bank' => [
                'account_type_label' => 'Account Type',
                'account_type_options' => ['Checking', 'Savings', 'Business'],
                'account_holder_label' => 'Account Holder Name',
                'account_number_label' => 'Account Number',
                'account_number_confirm_label' => 'Confirm Account Number',
                'bank_code_label' => 'Routing Number',
                'linked_mobile_label' => 'Registered Mobile',
                'bank_code_name' => 'ifsc_code',
                'bank_code_placeholder' => 'Routing Number',
            ],
        ],

        'AE' => [
            'dial_code' => '+971',
            'postal_label' => 'Postal Code',
            'turnover_units' => ['Thousand', 'Million', 'Billion'],
            'bank' => [
                'account_type_label' => 'Account Type',
                'account_type_options' => ['Personal', 'Business'],
                'account_holder_label' => 'Account Holder Name',
                'account_number_label' => 'IBAN / Account Number',
                'account_number_confirm_label' => 'Confirm IBAN / Account Number',
                'bank_code_label' => 'SWIFT / BIC Code',
                'linked_mobile_label' => 'Registered Mobile',
                'bank_code_name' => 'ifsc_code',
                'bank_code_placeholder' => 'SWIFT / BIC Code',
            ],
        ],

        'CN' => [
            'dial_code' => '+86',
            'postal_label' => 'Postal Code',
            'turnover_units' => ['Thousand', 'Million', 'Billion'],
            'bank' => [
                'account_type_label' => 'Account Type',
                'account_type_options' => ['Personal', 'Corporate'],
                'account_holder_label' => 'Account Holder Name',
                'account_number_label' => 'Bank Account Number',
                'account_number_confirm_label' => 'Confirm Bank Account Number',
                'bank_code_label' => 'Bank / SWIFT Code',
                'linked_mobile_label' => 'Registered Mobile',
                'bank_code_name' => 'ifsc_code',
                'bank_code_placeholder' => 'Bank / SWIFT Code',
            ],
        ],

        'EG' => ['dial_code' => '+20', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'ET' => ['dial_code' => '+251', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'ZA' => ['dial_code' => '+27', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'MX' => ['dial_code' => '+52', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'VE' => ['dial_code' => '+58', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'CO' => ['dial_code' => '+57', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'BR' => ['dial_code' => '+55', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'AR' => ['dial_code' => '+54', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'PE' => ['dial_code' => '+51', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'CL' => ['dial_code' => '+56', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'EC' => ['dial_code' => '+593', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'PY' => ['dial_code' => '+595', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'KR' => ['dial_code' => '+82', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'JP' => ['dial_code' => '+81', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],
        'TW' => ['dial_code' => '+886', 'postal_label' => 'Postal Code', 'turnover_units' => ['Thousand', 'Million', 'Billion'], 'bank' => ['account_type_label' => 'Account Type', 'account_type_options' => ['Personal', 'Business'], 'account_holder_label' => 'Account Holder Name', 'account_number_label' => 'Account Number / IBAN', 'account_number_confirm_label' => 'Confirm Account Number / IBAN', 'bank_code_label' => 'SWIFT / Bank Code', 'linked_mobile_label' => 'Registered Mobile', 'bank_code_name' => 'ifsc_code', 'bank_code_placeholder' => 'SWIFT / Bank Code']],

        'DEFAULT_INTL' => [
            'dial_code' => '+00',
            'postal_label' => 'Postal Code',
            'turnover_units' => ['Thousand', 'Million', 'Billion'],
            'bank' => [
                'account_type_label' => 'Account Type',
                'account_type_options' => ['Personal', 'Business'],
                'account_holder_label' => 'Account Holder Name',
                'account_number_label' => 'Account Number / IBAN',
                'account_number_confirm_label' => 'Confirm Account Number / IBAN',
                'bank_code_label' => 'SWIFT / Bank Code',
                'linked_mobile_label' => 'Registered Mobile',
                'bank_code_name' => 'ifsc_code',
                'bank_code_placeholder' => 'SWIFT / Bank Code',
            ],
        ],
    ],
];
