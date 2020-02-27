<?php

namespace App\Validation\Rules;

use App\Validation\Rules\Rule;

class EmailRule extends Rule
{

    /**
     * @inheritDoc
     */
    public function passes($field, $value, $data)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function message($field)
    {
        return $field . ' is not valid email';
    }
}