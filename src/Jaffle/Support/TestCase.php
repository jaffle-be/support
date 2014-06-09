<?php
/**
 * User: thomas
 * Date: 15/05/14
 * Time: 16:39
 */

namespace Jaffle\Support;


abstract class TestCase extends \PHPUnit_Framework_TestCase{

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function setAttribute(&$object, $attributeName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);

        return $property->setValue($object, $value);
    }

}