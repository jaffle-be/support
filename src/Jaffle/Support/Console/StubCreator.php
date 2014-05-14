<?php
/**
 * User: thomas
 * Date: 14/05/14
 * Time: 16:49
 */

namespace Jaffle\Support\Console;

use Illuminate\Filesystem\Filesystem;

class StubCreator {

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * @param $stub
     * @param array $data
     * @return bool|string return false on error
     */
    public function create($stub, array $data = array())
    {
        $stub = $this->files->get(__DIR__ . '/stubs/' . str_replace('.', '/', $stub) . '.stub');

        foreach ($data as $key => $value)
        {
            $stub = str_replace('{{' . $key  . '}}', $value, $stub);
        }

        return $stub;
    }

    public function save($path, $stub)
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if(!$this->files->isDirectory($directory))
        {
            $this->files->makeDirectory($directory, 0777, true);
        }

        $this->files->put($path, $stub);
    }
} 