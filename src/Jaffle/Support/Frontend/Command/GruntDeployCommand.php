<?php namespace Jaffle\Support\Frontend\Command;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;

class GruntDeployCommand extends \Illuminate\Console\Command {

    /**
     * The original path where we were before changing paths to execute stuff in another directory
     * @var
     */
    protected $originalPath;

    /**
     * @var Base path where our application is installed
     */
    protected $rootPath;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command name.
     * @todo define name
     * @var string
     */
    protected $name = 'frontend:deploy-grunt';

    /**
     * The console command description.
     * @todo define description
     * @var string
     */
    protected $description = 'Deploys the base for grunt automation of assets' ;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * @todo define the task being performed here
     */
    public function fire()
    {
        $this->setup();

        $this->go();
    }

    /**
     * Saves the current working directory
     * Asks for some general info
     */
    protected function setup()
    {
        $this->originalPath = getcwd();

        $this->rootPath = $this->laravel['path.base'];

        $this->createAssets();

        $this->name = $this->ask("What is the name of the application you're about to build?");

        if(empty($this->name))
        {
            $this->error('We need an application name to continue');

            exit();
        }
    }

    protected function go()
    {
        $this->gitIgnores();

        $this->packageJson();

        $this->bowerJson();

        $this->gruntFile();

        if(!$this->needBootstrap())
        {
            //do we need jquery if we don't need bootstrap?
            $this->needJquery();
        }
    }

    protected function createAssets()
    {
        //need a master file for backend
        $path = $this->rootPath . '/app/assets';

        $directories = array(
            '/javascript/frontend',
            '/javascript/backend',
            '/javascript/globals',
            '/stylesheets/frontend',
            '/stylesheets/backend',
            '/stylesheets/globals',
        );

        foreach($directories as $directory)
        {
            $this->directoryCreator($path . $directory);
        }

        $this->createAssetFiles($path);

        $this->info('created asset structures');
    }

    protected function createAssetFiles($path)
    {
        $files = array(
            '/javascript/frontend/application.js',
            '/javascript/backend/application.js',
            array('/stylesheets/frontend/main.less' => "@import './../globals/base.less';"),
            array('/stylesheets/backend/main.less' => "@import './../globals/base.less';"),
            '/stylesheets/globals/base.less',
        );

        foreach($files as $file)
        {
            $filename = $file;
            $content = "";

            if(is_array($file))
            {
                $keys = array_keys($file);
                $values = array_values($file);

                $filename = array_pop($keys);
                $content = array_pop($values);
            }
            $this->files->put($path . $filename, $content);
        }
    }
    
    protected function directoryCreator($path)
    {
        if(!$this->files->isDirectory($path))
        {
            $this->files->makeDirectory($path, null, true);
        }
    }

    /**
     * this will make sure unneeded files are not being uploaded to git repo
     *  - bower_components
     */
    protected function gitIgnores()
    {
        foreach(array('/bower_components', '/node_modules') as $directory)
        {
            $path = $this->rootPath . $directory;

            $this->directoryCreator($path);

            $this->gitIgnoreFile($path);
        }
    }

    protected function gitIgnoreFile($path)
    {
        $this->files->put($path . '/.gitignore', "*\n!.gitignore");
    }

    /**
     * Copy the package json file to the root of our app
     */
    protected function packageJson()
    {
        $content = $this->files->get(__DIR__ . '/../stubs/package.json');

        $content = str_replace('{{name}}', $this->name, $content);

        $this->files->put($this->rootPath . '/package.json', $content);

        $this->info("Don't forget to adjust the details in your package.json file");
    }

    /**
     * Copy the bower json file to the root of our app
     */
    protected function bowerJson()
    {
        $content = $this->files->get(__DIR__ . '/../stubs/bower.json');

        $content = str_replace('{{name}}', $this->name, $content);

        $this->files->put($this->rootPath . '/bower.json', $content);
    }

    protected function gruntFile()
    {
        $content = $this->files->get(__DIR__ . '/../stubs/Gruntfile.js');

        $this->files->put($this->rootPath . '/Gruntfile.js', $content);
    }

    /**
     * Check if the user wants jquery installed
     * @return bool
     */
    protected function needJquery()
    {
        if($this->confirm('Do you want jquery installed? (yes|no)'))
        {
            chdir($this->rootPath);

            passthru("bower install boostrap -S");
        }
    }

    /**
     * Check if the user wants bootstrap installed.
     * @return bool return true if he does so we can skip jquery question
     */
    protected function needBootstrap()
    {
        if($this->confirm('Do you want bootstrap installed? (yes|no)'))
        {
            chdir($this->rootPath);

            passthru("bower install bootstrap -S");

            return true;
        }

        return false;
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