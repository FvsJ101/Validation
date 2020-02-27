<?php

namespace App\Validation\Rules;

class BetweenRule extends Rule
{

    private $min;

    private $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function passes($field, $value, $data)
    {

        if (strlen($value) < $this->min) return false;

        if (strlen($value) > $this->max) return false;

        return true;

    }

    /**
     * @inheritDoc
     */
    public function message($field)
    {
        return $field . " needs to be between {$this->min} and {$this->max} length";
    }
}