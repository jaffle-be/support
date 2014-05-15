<?php

namespace Validator;

use Illuminate\Support\MessageBag;
use Jaffle\Support\TestCase;
use Jaffle\Support\Validator\FormValidator;
use Symfony\Component\Translation\Translator;

class FormValidatorErrorsTest extends TestCase{

    public function testAddingErrorsSetsOneParameterWithoutKey()
    {
        $validator = $this->validator();

        $this->invokeMethod($validator, 'addErrors', array('testError'));

        $errors = $validator->getValidationErrors();

        $this->assertSame(1, count($errors->all()));
        $this->assertSame('testError', $errors->first());
    }

    public function testAddingMultipleErrorsWithoutKey()
    {
        $validator = $this->validator();
        $this->invokeMethod($validator, 'addErrors', array(
            array('error1', 'error2')
        ));

        $errors = $validator->getValidationErrors();
        $errors = $errors->all();

        $this->assertCount(2,$errors);
        $this->assertSame('error1', $errors[0]);
        $this->assertSame('error2', $errors[1]);
    }

    public function testAddingOneErrorWithKey()
    {
        $validator = $this->validator();

        $this->invokeMethod($validator, 'addErrors', array(
            'value' => 'testvalue', 'key' => 'testkey'
        ));

        $errors = $validator->getValidationErrors();

        $this->assertCount(1, $errors->all());
        $this->assertTrue($errors->has('testkey'));
        $this->assertSame('testvalue', $errors->first('testkey'));
    }

    public function testAddingMessageOnSameKey()
    {
        $validator = $this->validator();

        $this->invokeMethod( $validator, 'addErrors', array(
            array(
                'test' => 'error 1'
            )
        ));

        $this->invokeMethod($validator, 'addErrors', array(
            array(
                'test' => 'this should be added',
            )
        ));

        $errors = $validator->getValidationErrors();

        $this->assertCount(2, $errors->all());
        $this->assertCount(2, $errors->get('test'));
    }

    /**
     * @return FormValidator
     */
    protected function validator()
    {
        $translator = new Translator('nl');
        $container = new \Illuminate\Container\Container();
        $factory = new \Illuminate\Validation\Factory($translator, $container);

        $messagebag = new MessageBag();

        return $this->getMockForAbstractClass(
            'Jaffle\Support\Validator\FormValidator',
            array($factory, $messagebag)
        );
    }

} 