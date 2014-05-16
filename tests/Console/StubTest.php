<?php

namespace Console;

use Illuminate\Filesystem\Filesystem;
use Jaffle\Support\TestCase;

use Jaffle\Support\Console\Stub;

class StubTest extends TestCase{

    /**
     * @var string
     */
    protected $filled = "<?php namespace Testnamespace;";

    public function testCreationInternalWorkings()
    {
        $stub = $this->getMock('Jaffle\Support\Console\Stub', array('load','fill'), array($this->filesystem()));

        $stub->expects($this->once())
            ->method('load');
        $stub->expects($this->once())
            ->method('fill')
            ->will($this->returnValue($this->filled));

        $result = $stub->create('something', array(
            'namespace' => 'Testnamespace'
        ));

        $this->assertSame($this->filled, $result);
    }

    public function testSavingInternalWorkings()
    {
        //add other calls to filesystem
        $filesystem = $this->filesystem();

        $filesystem->expects($this->once())
            ->method('isDirectory');
        $filesystem->expects($this->any())
            ->method('makeDirectory');

        $stub = $this->getMock('\Jaffle\Support\Console\Stub',null, array($filesystem));

        $result = $stub->create('something', array(
            'namespace' => 'Testnamespace'
        ));

        $stub->save($result, __DIR__);
    }

    public function testLoading()
    {
        $creator = $this->stub();

        $this->invokeMethod(
            $creator,
            'load',
            array(
                'stub' => 'file'
            )
        );

        //check the stub internal property
        $ref = new \ReflectionClass(get_class($creator));
        $stub = $ref->getProperty('stub');
        $stub->setAccessible(true);

        $this->assertSame($this->sampleStub(), $stub->getValue($creator));
    }

    public function testFilling()
    {
        $creator = $this->stub();

        $this->invokeMethod($creator, 'load', array(
            'stub' => 'file'
        ));

        $filled = $this->invokeMethod($creator, 'fill', array(
            'data' => array(
                'namespace' => 'Testnamespace'
            )
        ));

        $this->assertSame($this->filled, $filled);
    }

    /**
     * @return Stub
     */
    protected function stub()
    {
        return new Stub($this->filesystem());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function filesystem()
    {
        $filesystem = $this->getMock('Illuminate\Filesystem\Filesystem');

        $filesystem->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->sampleStub()));
        
        return $filesystem;
    }

    protected function sampleStub()
    {
        return "<?php namespace {{namespace}};";
    }

} 