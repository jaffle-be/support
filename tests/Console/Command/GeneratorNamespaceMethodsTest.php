<?php

namespace Console\Command;
use Jaffle\Support\TestCase;

class GeneratorNamespaceMethodsTest extends TestCase
{
    protected $namespace = 'testnamespace.sub.something.cool';

    protected $preparedNamespace = 'testnamespace sub something cool';

    protected $resultNamespace = 'Testnamespace\Sub\Something\Cool';

    protected $expectedFilename = 'Cool.php';

    protected $expectedClassname = 'Cool';

    protected $expectedPath = '../../../../../../sub/src/';

    public function testGettingFilenameWithoutExtension()
    {
//        $filename = $this->invokeMethod($generator, 'filename');
    }

    public function testVerification()
    {
        $gen = $this->generator();

        $this->assertFalse($this->invokeMethod($gen, 'verify', array(
            'namespace' => $this->namespace
        )));

        $this->AssertTrue($this->invokeMethod($gen, 'verify', array(
            'namespace' => 'Something with only spaces'
        )));
    }

    public function testSettingNamespace()
    {
        $gen = $this->generator();

        $this->invokeMethod($gen, 'setNamespace', array(
            'namespace' => $this->namespace
        ));

        $ref = new \ReflectionClass('Jaffle\Support\Console\Command\Generator');
        $namespace = $ref->getProperty('namespace');
        $namespace->setAccessible(true);

        $this->assertSame($this->resultNamespace, $namespace->getValue($gen));
    }

    public function testSettingClassname()
    {
        $gen = $this->generator();

        $this->invokeMethod($gen, 'setClassName', array(
            'namespace' => $this->namespace
        ));

        $ref = new \ReflectionClass('Jaffle\Support\Console\Command\Generator');
        $classname = $ref->getProperty('classname');
        $classname->setAccessible(true);

        $this->assertSame('Cool', $classname->getValue($gen));
    }

    public function testPrepareNamespace()
    {
        $gen = $this->generator();

        $result = $this->invokeMethod($gen, 'prepareNamespace', array(
            'namespace' => $this->namespace
        ));

        $this->assertSame($this->preparedNamespace, $result);
    }

    public function testSetNamespaceArguments()
    {
        $gen = $this->generator();

        $this->invokeMethod($gen, 'setNamespaceArguments', array(
            'namespace' =>$this->namespace
        ));

//        $ref = new \ReflectionClass('Jaffle\Support\Console\Command\Generator');
//        $namespace = $ref->getProperty('namespace');
//        $namespace->setAccessible(true);
//        $this->assertSame($this->resultNamespace, $namespace->getValue($gen));
//
//        $classname = $ref->getProperty('classname');
//        $classname->setAccessible(true);
//        $this->assertSame($this->expectedClassname, $classname->getValue($gen));

//        $path = $ref->getProperty('path');
//        $path->setAccessible(true);
//        $this->assertSame($this->expectedPath, $path->getValue($gen));

//        $filename = $ref->getProperty('filename');
//        $filename->setAccessible(true);
//        $this->assertSame($this->expectedFilename, $filename->getValue($gen));
    }

    protected function generator()
    {
        $stub = $this->stub();

        return $this->getMockForAbstractClass(
            'Jaffle\Support\Console\Command\Generator',
            array($stub, 'testcommand')
        );
    }

    protected function filesystem()
    {
        $filesystem = $this->getMock('Illuminate\Filesystem\Filesystem');
        $filesystem->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->sample()));

        return $filesystem;
    }

    protected function stub()
    {
        return $this->getMock(
            'Jaffle\Support\Console\Stub',
            null,
            array($this->filesystem())
        );
    }

    protected function sample()
    {
        return "<?php namespace {{namespace}}";
    }

} 