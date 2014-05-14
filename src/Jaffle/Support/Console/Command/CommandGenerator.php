<?php
/**
 * User: thomas
 * Date: 14/05/14
 * Time: 17:16
 */

namespace Jaffle\Support\Console\Command;

class CommandGenerator extends Generator{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dev:command:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a command for jaffle';

    /**
     * @todo define the task being performed here
     */
    public function fire()
    {
        $this->create('command');
    }
}