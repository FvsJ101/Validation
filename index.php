<?php

use App\Validation\Validator;

require "vendor/autoload.php";

$validator = new Validator();

$result = $validator->validate(
    [
        /*"first_name"  => 'mike',
        "middle_name" => 'mike',
        "last_name"   => 'meyer',*/
        /*"emails"       => [
            'testing@test.com',
            'testing',
            '',
        ],*/

        "users"       => [
            ['email' => 'testing@test.com', 'first_name' => 'mike'],
            ['email' => 'testing', 'first_name' => 'dale'],
            ['email' => '', 'first_name' => 'ash'],
        ],
    ],
    [
        /*'first_name' => [
            'required',
            'between:2,5',
            'required_with:last_name,middle_name',
        ],

        'last_name' => [
            'required'
        ],

        'middle_name' => [
            'required'
        ],*/

        'users.*.email' => [
            'required',
            'email'
        ],
        'users.*.first_name' => [
            'required',
        ],
    ],
    [
        /*'first_name'  => 'First name',
        'last_name'   => 'Last name',
        'middle_name' => 'Middle name',*/
        'email'       => 'Email address',
    ]
);

if (!$result) {
    dump("failed", $validator->getErrors());
} else {
    dump("passed", $result);
}


