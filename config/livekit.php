<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LiveKit Server URL
    |--------------------------------------------------------------------------
    |
    | URL of your LiveKit server that clients will connect to.
    |
    */

    'server_url' => env('LIVEKIT_SERVER_URL', 'ws://localhost:7880'),

    /*
    |--------------------------------------------------------------------------
    | LiveKit API Key
    |--------------------------------------------------------------------------
    |
    | API key for generating access tokens. Must match the key in
    | the LiveKit server config.
    |
    */

    'api_key' => env('LIVEKIT_API_KEY', 'local-dev-key'),

    /*
    |--------------------------------------------------------------------------
    | LiveKit API Secret
    |--------------------------------------------------------------------------
    |
    | Secret for signing access tokens. Must match the secret in
    | the LiveKit server config.
    |
    */

    'api_secret' => env('LIVEKIT_API_SECRET', 'local-dev-secret'),

];
