<?php
/**
 * User: thomas
 * Date: 14/05/14
 * Time: 16:20
 */

namespace Jaffle\Support\Console;

use Jaffle\Support\Console\Command\ServiceGenerator;
use Jaffle\Support\Console\Command\CommandGenerator;
use Jaffle\Support\Console\StubCreator;

class ConsoleServiceProvider extends \Illuminate\Support\ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('support::dev.generator.service', function($app)
        {
            return new ServiceGenerator(new StubCreator($app['files']));
        });

        $this->app->bindShared('support::dev.generator.command', function($app)
        {
            return new CommandGenerator(new StubCreator($app['stub']));
        });

        $this->commands(array('support::dev.generator.service', 'support::dev.generator.command'));
    }

	/**
     * Get the services provided by the provider.
     *
     * @return array
     */
	public function provides()
    {
        return array();
    }

	/**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
	public function when()
    {
        return array();
    }

}