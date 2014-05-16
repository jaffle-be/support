<?php

namespace Jaffle\Support\Console\Command;

use Jaffle\Support\Console\Stub;
use Str;
use Symfony\Component\Console\Input\InputArgument;

abstract class Generator extends \Illuminate\Console\Command{

    /**
     * @var Stub
     */
    protected $creator;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $classname;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param Stub $creator
     */
    public function __construct(Stub $creator, $name = null)
    {
        $this->creator = $creator;

        if(!empty($name) && is_string($name))
        {
            $this->name = $name;
        }

        parent::__construct();
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('namespace', InputArgument::REQUIRED, 'provide a namespace in `dot notation` which resides in a vendor/workbench package dir, starting from the src')
        );
    }

    /**
     * @param $stub
     * @param array $data
     */
    protected function create($stub, array $data = array())
    {
        if(empty($data['namespace']))
        {
            $data['namespace'] = $this->argument('namespace');
        }

        if($namespace = $data['namespace'])
        {
            $this->setNamespaceArguments($namespace);

            $data = array_merge($data, array(
                'namespace' => $this->namespace,
                'classname' => $this->classname
            ));
        }

        $stub = $this->creator->create($stub, $data);

        $this->save($stub);
    }

    /**
     * Parse namespace into all guessable parts
     * @param string $namespace
     */
    protected function setNamespaceArguments($namespace)
    {
        $namespace = $this->prepareNamespace($namespace);

        $this->setNamespace($namespace);

        $this->setClassname($namespace);

        $this->setPath($namespace);

        $this->setFilename($namespace);
    }

    /**
     * @param string $namespace
     * @return string
     */
    protected function prepareNamespace($namespace)
    {
        $namespace = str_replace('/', '.', $namespace);

        return str_replace('.', ' ', $namespace);
    }

    /**
     * @param $namespace
     * @return bool
     */
    protected function verify($namespace)
    {
        return !str_contains($namespace,'.') && !str_contains($namespace, '\\');
    }

    /**
     * @param $namespace
     */
    protected function setNamespace($namespace)
    {
        if(!$this->verify($namespace))
        {
            $namespace = $this->prepareNamespace($namespace);
        }

        $this->namespace = str_replace(' ' , '\\', ucwords($namespace));
    }

    /**
     * @param string $namespace
     */
    protected function setClassname($namespace)
    {
        if(!$this->verify($namespace))
        {
            $namespace = $this->prepareNamespace($namespace);
        }

        $namespace = ucwords($namespace);

        $this->classname = str_replace(' ', '\\', $namespace);
    }

    /**
     * @param string $namespace
     */
    protected function setPath($namespace)
    {
        $pieces = explode(' ', $namespace);

        array_pop($pieces);

        $namespace = implode(' ', $pieces);

        $path = ucwords(str_replace(' ', '/', ucwords($namespace)));

        $this->path = $this->reponise($namespace, $path);
    }

    protected function setFilename($namespace)
    {
        if(!$this->verify($namespace))
        {
            $namespace = $this->prepareNamespace($namespace);
        }

        $pieces = explode(' ', $namespace);

        $this->filename = array_pop($pieces);
    }

    /**
     * Add repository prefix if applicable
     * @param string $namespace
     * @param string $path
     * @return string
     */
    protected function reponise($namespace, $path)
    {
        $pieces = explode(' ', $namespace);

        $repo = isset($pieces[1]) ? $pieces[1] : false;

        if($repo)
        {
            /**
             * this means reverting back to where we are calling this from.
             * we actually asume that if this file is located in the workbench dir,
             * we want to make the file in the workbench dir
             *
             * the same goes for vendor.
             */
            $base = __DIR__ . '/../../../../../../';

            return $base . $repo .'/src/' . $path;
        }

        return $path;
    }

    /**
     * @param null|string $extension
     * @return string
     */
    protected function filename($extension = null)
    {
        return $this->filename . '.php';
    }

    /**
     * @param string $stub
     * @return bool|void
     */
    protected function save($stub)
    {
        $filename = $this->filename();

        if(!$this->path)
        {
            $this->error('ABORT ABORT no valid namespace provided');

            return false;
        }

        return $this->creator->save($stub, $this->path . '/' . $filename);
    }

} 