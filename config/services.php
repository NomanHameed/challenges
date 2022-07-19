<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'google' => [
        'client_id' => '526364222300-596m6kjscsk5j0epiih87sdn3mfqh8ro.apps.googleusercontent.com',
        'client_secret' => 'tmILFNNPC_TtKFCggZfZKWDM',
        'redirect' => 'http://127.0.0.1:8000/auth/google/callback',

    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'strava' => [
       'push_subscriptions_url' => env('STRAVA_PUSH_SUBSCRIPTIONS_URL'),
       'webhook_callback_url' => env('STRAVA_WEBHOOK_CALLBACK_URL'),
       'webhook_verify_token' => env('STRAVA_WEBHOOK_VERIFY_TOKEN'),
    ],
    
    'fitbit' => [
       'base_url' => env('FITBIT_BASE_URL', 'https://www.fitbit.com'),
       'api_base_url' => env('FITBIT_API_BASE_URL', 'https://api.fitbit.com'),
       'auth_code' => env('FITBIT_AUTH_CODE', '7b64c4b088b9c841d15bcac15d4aa7433d35af3e'),
       'verification_code' => env('FITBIT_VERIFICATION_CODE', ''),
    ],

    'MapMyRun' => [
       'client_key' => env('MAPMYRUN_CLIENT_KEY', 'svflww2l4l6blqhp57ywbolklhg4auda'),
       'client_secret' => env('MAPMYRUN_CLIENT_SECRET', 'cxmtyi7yk5beg4dshregy2gsim4zcu7pawyufs3mjjfmtcyb4ulneugcbyhwnzgz'),
       'redirect_uri' => env('MAPMYRUN_REDIRECT_URI', 'https://tracker.challengeinmotion.com/MapMyRunRedirect'),
       
    ],
    
    'garmin' => [
        'client_id' => env('GARMIN_KEY'),
        'client_secret' => env('GARMIN_SECRET'),
        'redirect_url' => env('GARMIN_CALLBACK_URI'),
    ],

];
