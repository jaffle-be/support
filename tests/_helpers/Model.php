<?php

namespace _Helpers;

class TranslationHelperModel extends \Jaffle\Support\Database\Eloquent\Model{

    protected $fillable = array('title', 'something');

    protected static $translations = array(
        'title'
    );

    public function __construct()
    {
        parent::__construct(array(
            'title_nl' => 'test_nl',
            'title_fr' => 'test_fr',
            'title_en' => 'test_en',
        ));
    }

}