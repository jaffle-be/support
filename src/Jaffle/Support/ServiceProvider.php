<?php

namespace Jaffle\Support;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    /**
     * Immediately load package
     * @var bool
     */
    public $defer = false;

    public function boot()
    {

    }

    public function register()
    {
        $this->package('jaffle-be/support', 'support');

        include(__DIR__ . '/../../start.php');
    }

    public function provides()
    {

    }

} 