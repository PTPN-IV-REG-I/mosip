<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shared secret for token broker
    |--------------------------------------------------------------------------
    |
    | This must be the same secret configured in the portal broker.
    |
    */
    'shared_secret' => env('SSO_SHARED_SECRET') ?? env('SSO_SECRET_KEY') ?? env('SSO_BROKER_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Expected issuer and audience
    |--------------------------------------------------------------------------
    */
    'issuer' => env('SSO_ISSUER', '1tep-portal'),
    'audience' => env('SSO_AUDIENCE', 'mosip'),

    /*
    |--------------------------------------------------------------------------
    | Optional external portal login URL
    |--------------------------------------------------------------------------
    */
    'portal_login_url' => env('SSO_PORTAL_LOGIN_URL'),

    /*
    |--------------------------------------------------------------------------
    | Allow GET consume for pilot/testing
    |--------------------------------------------------------------------------
    |
    | In production, prefer POST from portal.
    |
    */
    'allow_get_consume' => env('SSO_ALLOW_GET_CONSUME', false),

    /*
    |--------------------------------------------------------------------------
    | Auto-provision local account
    |--------------------------------------------------------------------------
    |
    | If false, only users already existing in MOSIP can use SSO.
    |
    */
    'auto_provision' => env('SSO_AUTO_PROVISION', false),

    /*
    |--------------------------------------------------------------------------
    | Role mapping from portal claim to MOSIP role
    |--------------------------------------------------------------------------
    */
    'role_map' => [
        'admin' => 'Admin',
        'superadmin' => 'Super Admin',
        'teppol' => 'Tekpol',
        'tekpol' => 'Tekpol',
        'user' => 'Tekpol',
    ],
];
