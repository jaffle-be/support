<?php
/**
 * User: thomas
 * Date: 18/05/14
 * Time: 10:54
 */

namespace Database\Eloquent;

use Illuminate\Console\Application;
use Illuminate\Events\Dispatcher;
use Jaffle\Support\Database\Eloquent\Observer;
use Jaffle\Support\TestCase;
use Jaffle\Support\Database\Eloquent\Model;

class ObserverTrackingTest extends TestCase{

    public function testTrackAt(){
        $observer = $this->observer();

        $model = $this->model();

        $this->invokeMethod($observer, 'trackAt', array(
            'model' => $model,
            'field' => 'trackable'
        ));

        $ref = new \ReflectionClass('Jaffle\Support\Database\Eloquent\Model');
        $attributes = $ref->getProperty('attributes');
        $attributes->setAccessible(true);

        $attributes = $attributes->getValue($model);

        $this->assertTrue($attributes['trackable_at'] instanceof \Carbon\Carbon);
    }

    public function testTrackBy()
    {
        $observer = $this->observer();

        $model = $this->model();

        $this->invokeMethod($observer, 'trackBy', array(
            'model' => $model,
            'field' => 'trackable'
        ));

        $ref = new \ReflectionClass('Jaffle\Support\Database\Eloquent\Model');
        $attributes = $ref->getProperty('attributes');
        $attributes->setAccessible(true);

        $attributes = $attributes->getValue($model);

        $this->assertSame(1, $attributes['trackable_by']);
    }

    public function testTrack()
    {
        $observer = $this->getMock(
            'Jaffle\Support\Database\Eloquent\Observer',
            array('trackBy', 'trackAt'),
            array($this->events(), $this->auth())
        );

        $observer->expects($this->once())
            ->method('trackBy')
            ->will($this->returnValue(null));
        $observer->expects($this->once())
            ->method('trackAt')
            ->will($this->returnValue(null));

        $model = $this->model();

        $this->invokeMethod($observer, 'track', array(
            'model' => $model,
            'field' => 'trackable'
        ));
    }

    protected function events()
    {
        return $this->getMock('Illuminate\Events\Dispatcher');
    }

    protected function auth()
    {
        $auth = $this->getMock('Illuminate\Auth\AuthManager', array('user'), array(
            'something mocked value for app'
        ));

        $user = new \stdClass();
        $user->id = 1;

        $auth->expects($this->any())
            ->method('user')
            ->will($this->returnValue($user));

        return $auth;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function observer()
    {
        return $this->getMock('Jaffle\Support\Database\Eloquent\Observer',
            null,
            array($this->events(), $this->auth()));
    }

    protected function model()
    {
        return new Model();
    }

} 