<?php

namespace Jaffle\Support\Console\Command;

use Jaffle\Support\Console\StubCreator;
use Str;

abstract class Generator extends \Illuminate\Console\Command{

    /**
     * @var StubCreator
     */
    protected $creator;

    /**
     * @var
     */
    protected $namespace;

    /**
     * @var
     */
    protected $filename;

    /**
     * @var
     */
    protected $classname;

    /**
     * @param StubCreator $creator
     */
    public function __construct(StubCreator $creator)
    {
        $this->creator = $creator;

        parent::__construct();
    }

    /**
     * @param $stub
     * @param array $data
     */
    protected function create($stub, array $data = array())
    {

        if($namespace = $data['namespace'])
        {
            $this->setNamespaceArguments($namespace);

            $data = array_merge(array(
                'namespace' => $this->namespace,
                'class' => $this->classname
            ), $data);
        }

        $stub = $this->creator->create($stub, $data);

        $this->save($stub);
    }

    protected function setNamespaceArguments($namespace)
    {
        $namespace = str_replace('/', '.', $namespace);

        $namespace = str_replace('.', ' ', $namespace);

        $this->namespace = str_replace(' ' , '\\', ucwords($namespace));

        $pieces = explode(' ', $namespace);

        $this->classname = ucwords(array_pop($pieces));

        $this->setPath($pieces);
    }

    protected function setPath(array $pieces)
    {
        $this->path = ucwords(str_replace(' ', '/', ucwords(implode(' ', $pieces))));

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

            $this->path = $base . $repo .'/src/' . $this->path;
        }
    }

    protected function filename($extension = null)
    {
        return $this->classname . '.php';
    }

    protected function save($stub)
    {
        $filename = $this->filename();

        return $this->creator->save($this->path . '/' . $filename, $stub);
    }

} 