<?php

namespace Jaffle\Support\Validator;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as Validator;
use Jaffle\Support\Exception\FormValidationException;

abstract class FormValidator {

    /**
     * The set of rules for the form
     * @var array
     */
    protected $rules;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Validator
     */
    protected $validation;

    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    public function __construct(Validator $validator, MessageBag $messages)
    {
        $this->validator = $validator;

        $this->errors = $messages;
    }

    public function validate(array $formData)
    {
        $this->validation = $this->validator->make($formData, $this->getValidationRules());

        if($this->validation->fails())
        {
            $this->addErrors($this->validation->errors());

            throw new FormValidationException('Form failed', $this->getValidationErrors());
        }
    }

    protected function addErrors($value, $key = null)
    {
        if(is_string($value))
        {
            $value = empty($key) ? (array) $value : array($key => $value);
        }

        $this->errors->merge($value);
    }

    protected function getValidationRules()
    {
        return $this->rules;
    }

    public function getValidationErrors()
    {
        return $this->errors;
    }

} 