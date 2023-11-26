<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Users
    |--------------------------------------------------------------------------
    |
    | This is a list of users whoa re authoruzed to authorize themeselfs to get a token.
    |
    */
    
    'users' => [
        // 'username' => [password, name]
        'sepamak' => [
            'password' => 'A8B33!Mi$8Q9uN20!aBx',
            'name' => 'Sepamak portal',
            'created_at' => '2022-07-23',
            'active' => true
        ],
        'developer' => [
            'password' => 'D3vel0p3R!1368',
            'name' => 'Developer access',
            'created_at' => '2022-07-26',
            'active' => true
        ],
    ],

    'access_token' => [
        'expire_time' => 86400, //1 day
    ],

    'errors' => [
        '1001' => 'username and password are required.',
        '1002' => 'Username or password is invalid.',
        '1003' => 'Username or password is invalid.',
        '1004' => 'Validation error',
        '1005' => 'Under maintenance',
        '1006' => 'Unexpected error',
        '1007' => 'The user has been disabled temporary by the administrator'
    ],

    'messages' => [
        'OK' => 'Operation Successful'
    ]
];