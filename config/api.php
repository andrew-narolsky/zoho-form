<?php

return [
    'zoho' => [
        'url' => env('ZOHO_API_URL', ''),
        'code' => env('ZOHO_API_CODE', ''),
        'client_id' => env('ZOHO_API_CLIENT_ID', ''),
        'client_secret' => env('ZOHO_API_CLIENT_SECRET', ''),
        'access_token' => env('ZOHO_API_ACCESS_TOKEN', ''),
        'refresh_token' => env('ZOHO_API_REFRESH_TOKEN', ''),
        'api_domain' => env('ZOHO_API_DOMAIN', ''),
    ],
];
