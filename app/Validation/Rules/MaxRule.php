<?php

namespace App\Validation\Rules;

class MaxRule extends Rule
{

    protected $maxLength;

    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @inheritDoc
     */
    public function passes($field, $value, $data)
    {
        return strlen($value) < $this->maxLength;
    }

    /**
     * @inheritDoc
     */
    public function message($field)
    {
        return $field . " must be a max of {$this->maxLength} char";
    }
}