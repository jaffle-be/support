<?php namespace Jaffle\Support\Frontend\Command;

class InstallCommand extends \Illuminate\Console\Command {

    /**
     * The console command name.
     * @todo define name
     * @var string
     */
    protected $name = 'frontend:grunt-install';

    /**
     * The console command description.
     * @todo define description
     * @var string
     */
    protected $description = 'The command to run to install all dependencies to run the frontend module' ;

    /**
     * @todo define the task being performed here
     */
    public function fire()
    {
        $dir = getcwd();

        chdir('' . __DIR__ . '/../');

        passthru("sh install.sh");

        chdir($dir);
    }

    /**
     * Get the console command arguments.
     * @todo define any arguments or delete this function for cleanup
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     * @todo define any options or delete this function for cleanup
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}