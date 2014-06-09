<?php namespace Jaffle\Support\Frontend\Command;

use Illuminate\Filesystem\Filesystem;

class TemplatesCommand extends \Illuminate\Console\Command {

    /**
     * The console command name.
     * @todo define name
     * @var string
     */
    protected $name = 'frontend:templates';

    /**
     * The console command description.
     * @todo define description
     * @var string
     */
    protected $description = 'install all blade template files' ;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * We could use scandir to build this array and run it on the templating directory,
     * but KISS!
     * @var array
     */
    protected $templates = array(
        'layouts/backend.blade.php',
        'layouts/frontend.blade.php',
        'layouts/email.blade.php',

        'layouts/backend/header.blade.php',
        'layouts/backend/footer.blade.php',

        'layouts/frontend/header.blade.php',
        'layouts/frontend/footer.blade.php',
    );

    /**
     * @todo define the task being performed here
     */
    public function fire()
    {
        if($this->noExistingTemplates())
        {
            $this->createTemplates();

            $this->info('All template files have been created');
        }
        else
        {
            $this->error('At least 1 template file already exists. Aborting mission');
        }
    }

    protected function getBase()
    {
        $base = $this->laravel['path'];

        return $base . '/' . 'views/';
    }

    protected function noExistingTemplates()
    {
        $base = $this->getBase();

        foreach($this->templates as $template)
        {
            if(file_exists($base . $template))
            {
                return false;
            }
        }

        return true;
    }

    protected function createTemplates()
    {
        $base = $this->getBase();

        $dir = __DIR__ . '/../../../../templating/';

        $this->createDirectories();

        foreach($this->templates as $template)
        {
            $this->files->copy($dir . $template, $base . $template);
        }
    }

    protected function createDirectories()
    {
        $dirs = array(
            'layouts/frontend',
            'layouts/backend'
        );

        $base = $this->getBase();

        foreach($dirs as $dir)
        {
            if(!$this->files->isDirectory($base . $dir))
            {
                $this->files->makeDirectory($base . $dir, 0777, true);
            }

        }
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