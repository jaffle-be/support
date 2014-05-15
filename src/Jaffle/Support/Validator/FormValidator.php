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

    /**
     * Validate a form by its data and a set of rules
     * @param array $formData
     * @return bool
     * @throws \Jaffle\Support\Exception\FormValidationException
     */
    public function validate(array $formData)
    {
        $this->validation = $this->validator->make($formData, $this->getValidationRules());

        if($this->validation->fails())
        {
            $this->addErrors($this->validation->errors());

            throw new FormValidationException('Form failed', $this->getValidationErrors());
        }

        return true;
    }

    /**
     * @param $value
     * @param null $key
     * @return MessageBag
     */
    protected function addErrors($value, $key = null)
    {
        if(is_string($value))
        {
            if(empty($key))
            {
                $value = array($value);
            }
            else{
                $value = array($key => $value);
            }
        }

        return $this->errors->merge($value);
    }

    protected function getValidationRules()
    {
        return $this->rules;
    }

    /**
     * @return MessageBag
     */
    public function getValidationErrors()
    {
        return $this->errors;
    }

} 