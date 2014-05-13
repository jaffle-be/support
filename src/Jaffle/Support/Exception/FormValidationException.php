<?php
/**
 * User: thomas
 * Date: 13/05/14
 * Time: 14:08
 */

namespace Jaffle\Support\Exception;

class FormValidationException extends \Exception{

    /**
     * @var array
     */
    protected $errors;

    /**
     * @param string $message
     * @param array $errors
     */
    function __construct($message, $errors = array())
    {
        $this->errors = $errors;

        parent::__construct($message);
    }

    public function getErrors()
    {
        return $this->errors;
    }


} 