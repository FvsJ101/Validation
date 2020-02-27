<?php

use App\Validation\Validator;

require "vendor/autoload.php";

$validator = new Validator();

$result = $validator->validate(
    [
        "first_name" => '',
        "last_name" => 'Mike',
        "email" => '',
    ],
    [
        'first_name' => [
            'required',
            'between:2,5',
            'required_with:last_name',
        ],

        'last_name' => [
            'required'
        ],

        'email' => [
            'required',
            'email'
        ],
    ],
    [
        'first_name' => 'First name',
        'email' => 'Email address',
    ]
);

if (!$result) {
    dump($validator->getErrors());

}else {
    dump($result);
}


