<?php

namespace App\Validation;

use App\Validation\Rules\BetweenRule;
use App\Validation\Rules\EmailRule;
use App\Validation\Rules\MaxRule;
use App\Validation\Rules\OptionalRule;
use App\Validation\Rules\RequiredWithRule;
use App\Validation\Rules\RequireRule;

class RuleMap
{
    /**
     * @var array
     */
    protected static $map = [
        'max'           => MaxRule::class,
        'email'         => EmailRule::class,
        'between'       => BetweenRule::class,
        'optional'      => OptionalRule::class,
        'required'      => RequireRule::class,
        'required_with' => RequiredWithRule::class,
    ];

    /**
     * @param $rule
     * @param $options
     * @return mixed
     */
    public static function resolve($rule, $options)
    {
        return new static::$map[$rule](...$options);
    }

}