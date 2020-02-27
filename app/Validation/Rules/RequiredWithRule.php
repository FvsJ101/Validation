<?php

namespace App\Validation\Rules;

use App\Validation\Validator;

class RequiredWithRule extends Rule
{

    /**
     * @var array $fields
     */
    protected $fields;

    public function __construct(...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * @inheritDoc
     */
    public function passes($field, $value, $data)
    {
        foreach ($this->fields as $field) {
            if ($value === '' && $data[$field] !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message($field)
    {

        $aliases = Validator::getAliases($this->fields);

        return $field . ' is required with '.  strtolower(implode(', ', $aliases));
    }
}