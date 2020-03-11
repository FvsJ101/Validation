<?php

namespace App\Validation;

use App\Validation\Errors\ErrorBag;
use App\Validation\Rules\OptionalRule;
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
        $this->setData($this->extractWildCardData($data));
        $this->setRules($rules);
        $this->setAliases($aliases);


        foreach ($this->rules as $field => $rules) {
            $resolvedRules = $this->resolveRules($rules);

            foreach ($resolvedRules as $rule) {

                $this->validateRule($field, $rule, $this->resolvedRulesContainsOptional($resolvedRules));
            }
        }

        return $this->errors->hasErrors();
    }

    protected function resolvedRulesContainsOptional(array $rules)
    {

        foreach ($rules as $rule) {

            if ($rule instanceOf OptionalRule) {
                return true;
            }

        }

        return false;
    }

    /**
     * @param array $rules
     * @return array
     */
    protected function resolveRules(array $rules)
    {
        return array_map(
            function ($rule) {
                #TODO refactor
                //return (is_string($rule))? $this->getRuleFromString($rule) : $rule;

                if (is_string($rule)) {
                    return $this->getRuleFromString($rule);
                }

                return $rule;
            },
            $rules
        );
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
     * @param bool $optional
     */
    protected function validateRule($field, Rule $rule, $optional = false)
    {

        foreach ($this->getMatchingData($field) as $matchedField) {

            if ($value = $this->getFieldValue($matchedField, $this->data) === '' || $optional) {
                continue;
            }

            $this->validateUsingRuleObject($matchedField, $value, $rule);
        }
    }

    /**
     * @param $field
     * @param $value
     * @param Rule $rule
     */
    protected function validateUsingRuleObject($field, $value, Rule $rule)
    {
        if (!$rule->passes($field, $value, $this->data)) {
            $this->errors->addError(
                $field,
                $rule->message(self::getAlias($field))
            );
        }
    }

    /**
     * @param $field
     * @return array
     */
    protected function getMatchingData($field)
    {
        return preg_grep('/^' . str_replace('*', '([^\.]+)', $field) . '/', array_keys($this->data));
    }

    /**
     * @param array $array
     * @param string $root
     * @param array $results
     * @return array
     */
    protected function extractWildCardData(array $array, $root = '', $results = [])
    {
        foreach ($array as $key => $value) {
            if (is_iterable($value)) {
                $results = array_merge($results, $this->extractWildCardData($value, $root . $key . '.'));
            } else {
                $results[$root . $key] = $value;
            }
        }

        return $results;
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
        return array_map(
            function ($field) {
                return self::getAlias($field);
            },
            $fields
        );
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