<?php

namespace Validator;

use Illuminate\Support\MessageBag;
use Jaffle\Support\TestCase;
use Jaffle\Support\Validator\FormValidator;
use Symfony\Component\Translation\Translator;

class FormValidatorValidationTest extends TestCase{

    /**
     * @expectedException \Jaffle\Support\Exception\FormValidationException
     */
    public function testValidationThrowsErrorOnFailure()
    {
        $validator = $this->validator();

        //make one required attribute
        $this->setAttribute($validator, 'rules', array(
            'test' => 'required'
        ));

        $validator->validate(array());
    }

    public function testValidationOnEmptyRules()
    {
        $validator = $this->validator();

        $this->setAttribute($validator, 'rules', array());

        $this->assertTrue($validator->validate(array()));
    }

    public function testIfMethodReturnsTrueOnSucces()
    {
        $validator = $this->validator();

        $this->setAttribute($validator, 'rules', array(
            'test' => 'required'
        ));

        $this->assertTrue($validator->validate(array('test' => 'test')));
    }

    public function testValidationProperlyAddsErrors()
    {
        $validator = $this->validator();

        $this->setAttribute($validator, 'rules', array(
            'test' => 'required'
        ));

        try{
            $validator->validate(array());
        }
        //testing on the exception has its on test class
        catch(\Jaffle\Support\Exception\FormValidationException $e)
        {
            $this->assertCount(
                1,
                $validator->getValidationErrors()->all()
            );

            $this->assertSame(
                'validation.required',
                $validator->getValidationErrors()->first()
            );
        }
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