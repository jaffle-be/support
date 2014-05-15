<?php
/**
 * User: thomas
 * Date: 15/05/14
 * Time: 18:37
 */

namespace Exception;

use Illuminate\Support\MessageBag;
use Jaffle\Support\Exception\FormValidationException as E;
use Jaffle\Support\TestCase;

class FormValidationExceptionTest extends TestCase{

    /**
     * if you ever change this exception, you should propably test
     * setting the errors with reflection method,
     * and getting the errors in a different method
     */
    public function testIfErrorsGetSet()
    {
        try{
            throw new E (
                'something went terribly wrong',
                new MessageBag(array('a', 'bunch', 'of', 'errors'))
            );
        }
        catch(E $e)
        {
            $errors = $e->getErrors()->all();
            $this->assertCount(4, $errors);

            $this->assertSame('a', $errors[0]);
            $this->assertSame('bunch', $errors[1]);
            $this->assertSame('of', $errors[2]);
            $this->assertSame('errors', $errors[3]);
        }
    }

    public function testReturningErrors()
    {
        $exception = new E('message', new MessageBag(array('error1', 'error2')));
        $errors = $exception->getErrors();
        $this->assertCount(2, $errors->all());
        $this->assertSame('error1', $errors->all()[0]);
        $this->assertSame('error2', $errors->all()[1]);
    }

} 