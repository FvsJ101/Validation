<?php

namespace App\Validation\Rules;

abstract class Rule
{
    /**
     * @param $field
     * @param $value
     * @param array $data
     * @return mixed
     */
    abstract public function passes($field, $value, $data);

    /**
     * @param $field
     * @return mixed
     */
    abstract public function message($field);
}