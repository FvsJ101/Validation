<?php

use App\Validation\Validator;

require "vendor/autoload.php";

$validator = new Validator();

$result = $validator->validate(
    [
        "first_name" => '',
        "middle_name" => '',
        "last_name" => '',
        "email" => '',
    ],
    [
        'first_name' => [
            'required',
            'between:2,5',
            'required_with:last_name,middle_name',
        ],

        'last_name' => [
            'required'
        ],

        'middle_name' => [
            'required'
        ],

        'email' => [
            'required',
            'email'
        ],
    ],
    [
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'middle_name' => 'Middle name',
        'email' => 'Email address',
    ]
);

if (!$result) {
    dump($validator->getErrors());

}else {
    dump($result);
}


