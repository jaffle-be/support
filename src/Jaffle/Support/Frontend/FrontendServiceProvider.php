<?php namespace Jaffle\Support\Frontend;

class FrontendServiceProvider extends \Illuminate\Support\ServiceProvider{


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
        $this->app->bindShared('support::dev.frontend.install', function($app)
        {
            return new Command\InstallCommand();
        });

        $this->app->bindShared('support::dev.frontend.deploy-grunt', function($app){
            return new Command\GruntDeployCommand($app['files']);
        });

        $this->app->bindShared('support::dev.frontend.install-templates', function($app){
            return new Command\TemplatesCommand($app['files']);
        });

        $this->commands(array(
            'support::dev.frontend.install',
            'support::dev.frontend.deploy-grunt',
            'support::dev.frontend.install-templates'
        ));
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