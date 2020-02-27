<?php

namespace App\Validation\Rules;

use App\Validation\Rules\Rule;

class RequireRule extends Rule
{
    /**
     * @param $filed
     * @param $value
     * @param $data
     * @return bool
     */
    public function passes($filed, $value, $data)
    {
        return !empty($value);
    }

    /**
     * @param $field
     * @return string
     */
    public function message($field)
    {
        return $field . ' is required';
    }
}