<?php
/**
 * User: thomas
 * Date: 14/05/14
 * Time: 16:31
 */

namespace Jaffle\Support\Console\Command;

class ServiceGenerator extends Generator{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dev:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a service provider for jaffle';

    /**
     * @todo define the task being performed here
     */
    public function fire()
    {
        $this->create('serviceProvider');
    }
} 