<?php

namespace Database\Eloquent;

use _Helpers\TranslationHelperModel;
use Jaffle\Support\TestCase;

class ModelTranslationsTest extends TestCase{

    public function testGettingDefaultLocale(){
        $model = $this->model();

        $this->assertSame('en', $this->invokeMethod($model, 'defaultLocale'));
    }

    public function testGettingFillablesReplacesTranslations()
    {
        $model = $this->model();

        $fillables = $model->getFillable();

        $this->assertContains('title_nl', $fillables);
        $this->assertContains('title_fr', $fillables);
        $this->assertContains('title_en', $fillables);
    }

    public function testCanBeTranslated()
    {
        $model = $this->model();

        $this->assertTrue($this->invokeMethod($model, 'canBeTranslated', array('attribute' => 'title')));
    }

    public function testDynamicGetter()
    {
        $model = $this->model();

        $this->assertSame('test_en', $this->invokeMethod($model, '__get', array('attribute' => 'title')));
    }

    public function testFilteringFillableAttributes()
    {
        $model = $this->model();

        $start = array('title_nl' => 'value','something' => 'value', 'will be removed' => 'value');
        //expected endresult
        $end = array('title_nl', 'something');

        $result = $this->invokeMethod($model, 'fillableFromArray', array('attributes' => $start));
        $this->assertArrayHasKey('title_nl', $result);
        $this->assertArrayHasKey('something', $result);
        $this->assertArrayNotHasKey('will be removed', $result);
    }

    public function testIfIsFillableWorksOnTranslationableFields()
    {
        $model = $this->model();

        $this->assertTrue($this->invokeMethod($model, 'isFillable', array('attribute' => 'title_fr')));
    }

    public function testActualTranslateMethod()
    {
        $model = $this->model();

        $this->assertSame('test_en', $model->translate('title'));
    }

    protected function model()
    {
        return new TranslationHelperModel();
    }


} 