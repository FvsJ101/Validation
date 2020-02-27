<?php

namespace App\Validation\Errors;

class ErrorBag
{
    protected $errors = [];

    /**
     * @param $key
     * @param $value
     */
    public function addError($key, $value)
    {
        $this->errors[$key][] = $value;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors);
    }
}