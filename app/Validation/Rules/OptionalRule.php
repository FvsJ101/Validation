<?php

namespace App\Validation\Rules;

class OptionalRule extends Rule
{

    /**
     * @inheritDoc
     */
    public function passes($field, $value, $data)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function message($field)
    {
        return null;
    }
}