<?php

namespace Jaffle\Support\Database\Eloquent;

use Validator;
use Illuminate\Events\Dispatcher;

class Observer {

    /**
     * Validation rules to follow
     * Keep these static to not duplicate them on each request
     * @var array
     */
    protected static $rules = array();

    /**
     * Patterns to be replaced in the validation rules.
     *
     * key = name of the field to validate
     * value = an array with
     *  - a key as the pattern to replace
     *  - a value as what to replace the pattern with
     *    while referencing an attribute of the current model
     *
     * Keep these static to not duplicate them on each request
     * @var array
     */
    protected static $patterns = array();

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    protected function enforce($model, $rules)
    {
        if(isset(static::$patterns))
        {
            foreach(static::$patterns as $field => $patterns)
            {
                foreach($patterns as $pattern => $replacement)
                {
                    $rules[$field] = preg_replace($pattern, $model->getAttribute($replacement), $rules[$field]);
                }
            }
        }

        return $rules;
    }

    /**
     * Get a validator for a model by a predefined ruleset
     * @param $model \Jaffle\Support\Eloquent\Model the model to validate
     * @param $rule string the key to use from the rules array
     */
    protected function getValidator($model, $rule)
    {
        if(isset(static::$rules[$rule]))
        {
            return Validator::make($model->getAttributes(), $this->enforce($model, static::$rules[$rule]));
        }

        throw new ValidatorRulesetNotFound($rule . ' is an unknown ruleset for ' . get_class($this));

    }

} 