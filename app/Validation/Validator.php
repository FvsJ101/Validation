<?php

namespace App\Validation;

use App\Validation\Errors\ErrorBag;
use App\Validation\Rules\Rule;

class Validator
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ErrorBag $errors
     */
    protected $errors;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected static $aliases;

    public function __construct()
    {
        $this->errors = new ErrorBag();
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $aliases
     * @return bool
     */
    public function validate(array $data, array $rules, array $aliases)
    {
        $this->setData($data);
        $this->setRules($rules);
        $this->setAliases($aliases);

        foreach ($this->rules as $field => $rules) {
            foreach ($this->resolveRules($rules) as $rule) {
                $this->validateRule($field, $rule);
            }
        }

        return $this->errors->hasErrors();
    }

    /**
     * @param array $rules
     * @return array
     */
    protected function resolveRules(array $rules)
    {

        return array_map(function ($rule) {

            #TODO refactor
            //return (is_string($rule))? $this->getRuleFromString($rule) : $rule;

            if (is_string($rule)) {
                return $this->getRuleFromString($rule);
            }

            return $rule;
        }, $rules);

    }

    /**
     * @param $rule
     * @return mixed
     */
    protected function getRuleFromString($rule)
    {

        return $this->newRuleFromMap(
            ($exploded = explode(':', $rule))[0],
            explode(',', end($exploded))
        );

    }

    /**
     * @param $rule
     * @param $options
     * @return mixed
     */
    protected function newRuleFromMap($rule, $options)
    {
        return RuleMap::resolve($rule, $options);
    }

    /**
     * @param $field
     * @param Rule $rule
     */
    protected function validateRule($field, Rule $rule)
    {

        if (!$rule->passes($field, $this->getFieldValue($field, $this->data), $this->data)){
            $this->errors->addError(
                $field,
                $rule->message(self::getAlias($field))
            );
        }
    }

    /**
     * @param $field
     * @return mixed
     */
    public static function getAlias($field)
    {
        return self::$aliases[$field] ?? $field;
    }

    /**
     * @param array $fields
     * @return mixed
     */
    public static function getAliases(array $fields)
    {

        return array_map(function ($field) {
            return self::getAlias($field);
        }, $fields);

    }

    /**
     * @param $fieldName
     * @param array $data
     * @return string
     */
    protected function getFieldValue($fieldName, array $data)
    {
        return $data[$fieldName] ?? null;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $aliases
     */
    public function setAliases(array $aliases)
    {
        self::$aliases = $aliases;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors->getErrors();
    }

}