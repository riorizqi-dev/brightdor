<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Account
    |--------------------------------------------------------------------------
    |
    | Credentials used by the seeder to create the initial admin account.
    | Override via environment variables, typically in .env.
    |
    */

    'admin' => [
        'name' => env('BRIGHTDOR_ADMIN_NAME', 'BrightDor Admin'),
        'email' => env('BRIGHTDOR_ADMIN_EMAIL', 'admin@brightdor.test'),
        'password' => env('BRIGHTDOR_ADMIN_PASSWORD', 'password'),
    ],

];
