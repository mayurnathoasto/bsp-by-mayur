<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Countries
    |--------------------------------------------------------------------------
    */

    'supported' => [
        'IN' => ['name' => 'India', 'currency' => 'INR', 'live' => true, 'city_selector' => true, 'homepage_route' => true],
        'AE' => ['name' => 'UAE', 'currency' => 'AED', 'live' => true, 'city_selector' => true, 'language_target' => 'ar', 'language_name' => 'Arabic', 'language_rtl' => true, 'homepage_route' => false],
        'US' => ['name' => 'USA', 'currency' => 'USD', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'UK' => ['name' => 'UK', 'currency' => 'GBP', 'live' => true, 'city_selector' => true, 'google_maps_code' => 'gb', 'homepage_route' => false],
        'CA' => ['name' => 'Canada', 'currency' => 'CAD', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'AU' => ['name' => 'Australia', 'currency' => 'AUD', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'CN' => ['name' => 'China', 'currency' => 'CNY', 'live' => true, 'city_selector' => true, 'language_target' => 'zh-CN', 'language_name' => 'Chinese', 'homepage_route' => false],

        'AT' => ['name' => 'Austria', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'BE' => ['name' => 'Belgium', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'BG' => ['name' => 'Bulgaria', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'HR' => ['name' => 'Croatia', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'CY' => ['name' => 'Cyprus', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'CZ' => ['name' => 'Czech Republic', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'DK' => ['name' => 'Denmark', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'EE' => ['name' => 'Estonia', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'FI' => ['name' => 'Finland', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'FR' => ['name' => 'France', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'DE' => ['name' => 'Germany', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'GR' => ['name' => 'Greece', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'HU' => ['name' => 'Hungary', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'IE' => ['name' => 'Ireland', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'IT' => ['name' => 'Italy', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'LV' => ['name' => 'Latvia', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'LT' => ['name' => 'Lithuania', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'LU' => ['name' => 'Luxembourg', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'MT' => ['name' => 'Malta', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'NL' => ['name' => 'Netherlands', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'PL' => ['name' => 'Poland', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'PT' => ['name' => 'Portugal', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'RO' => ['name' => 'Romania', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'SK' => ['name' => 'Slovakia', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'SI' => ['name' => 'Slovenia', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'ES' => ['name' => 'Spain', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'SE' => ['name' => 'Sweden', 'currency' => 'EUR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],

        'EG' => ['name' => 'Egypt', 'currency' => 'EGP', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'ET' => ['name' => 'Ethiopia', 'currency' => 'ETB', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'ZA' => ['name' => 'South Africa', 'currency' => 'ZAR', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'MX' => ['name' => 'Mexico', 'currency' => 'MXN', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'VE' => ['name' => 'Venezuela', 'currency' => 'VES', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'CO' => ['name' => 'Colombia', 'currency' => 'COP', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'BR' => ['name' => 'Brazil', 'currency' => 'BRL', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'AR' => ['name' => 'Argentina', 'currency' => 'ARS', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'PE' => ['name' => 'Peru', 'currency' => 'PEN', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'CL' => ['name' => 'Chile', 'currency' => 'CLP', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'EC' => ['name' => 'Ecuador', 'currency' => 'USD', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'PY' => ['name' => 'Paraguay', 'currency' => 'PYG', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'KR' => ['name' => 'South Korea', 'currency' => 'KRW', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'JP' => ['name' => 'Japan', 'currency' => 'JPY', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
        'TW' => ['name' => 'Taiwan', 'currency' => 'TWD', 'live' => true, 'city_selector' => true, 'homepage_route' => false],
    ],

    'aliases' => [
        'GB' => 'UK',
    ],

    'default_country' => 'IN',
    'default_currency' => 'INR',
];
