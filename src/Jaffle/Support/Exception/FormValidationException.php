<?php
/**
 * User: thomas
 * Date: 13/05/14
 * Time: 14:08
 */

namespace Jaffle\Support\Exception;
use Illuminate\Support\MessageBag;

class FormValidationException extends \Exception{

    /**
     * @var \Illuminate\Support\MessageBag|array
     */
    protected $errors;

    /**
     * @param string $message
     * @param \Illuminate\Support\MessageBag|array $errors
     */
    function __construct($message, $errors = array())
    {
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * @return \Illuminate\Support\MessageBag|array
     */
    public function getErrors()
    {
        return $this->errors;
    }


} 