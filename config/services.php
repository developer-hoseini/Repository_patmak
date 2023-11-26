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
    'sabteahval' => [
        'domain' => '',
    ],
    'payment' => [
        'base_url' => 'https://pec.shaparak.ir/NewPG/',
        'hmac_algo' => 'sha256',
        'merchant' => '213143400000999',
        'terminal' => '71000999',
        'icvk' => '5e0d59f1737eea448c403fc928495c14e00d6e4beb246be9f28986d91d423950e6d86787faf174cdc0c0f9b85c0e5275d22a2c22f063e883d15da4befd0469e3', //privatekey
        'test_gtp_identifier' => '341036382140120001000000000000',
        'revert_url' => '/order/{order_id}/bank-return'
    ],

    'commission_amount_person_regular' => env('PKH_COMMISSION_AMOUNT_PERSON_REGULAR' , 5000), // کارمزد شخص حقیقی
    'commission_amount_person_legal' => env('PKH_COMMISSION_AMOUNT_PERSON_LEGAL' , 5000), // کارمزد شخص حقوقی

    'magfa' => [
        'username' => 'moavenatmaskan_41372',
        'password' => 'IGJQDVNMzdGDEAOL',
        'source_number' => '300041372',
        'domain' => 'mrud'
    ],

    'chaapaar' => [
        'base_url' => 'https://sr-tuix.mrud.ir',
        'api_key' => 'Du6ryfKixgPMLctE',
        'identifier_key' => '1010',
        'hash_key' => 'eDFT6CRl81HmqJLUzvwNgA0Q2WBaO7Kd',

    ],

    'tuix' => [
        'base_url' => 'https://sr-tuix.mrud.ir',
        'username' => 'nezam_pey',
        'password' => 'H7*j3D1D5t@w7'
    ],

    'sabteahval' => [
        'base_url' => 'https://sr-tuix.mrud.ir',
        'url' => 'https://sr-tuix.mrud.ir/services/GSB_SabteAhval_Online?wsdl',
    ],

    'sabteasnad' => [
        'base_url' => 'https://sr-tuix.mrud.ir',
        'url' => 'https://sr-tuix.mrud.ir/services/GSB_SabteAsnad_Legal?wsdl',
    ]

];
