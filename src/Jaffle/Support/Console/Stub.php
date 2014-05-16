<?php
/**
 * User: thomas
 * Date: 14/05/14
 * Time: 16:49
 */

namespace Jaffle\Support\Console;

use Illuminate\Filesystem\Filesystem;

class Stub{

    protected $stub;

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
        $this->load($stub);

        return $this->fill($data);
    }

    public function save($stub, $path)
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if(!$this->files->isDirectory($directory))
        {
            $this->files->makeDirectory($directory, 0777, true);
        }

        $this->files->put($path, $stub);
    }

    /**
     * @param $stub
     */
    protected function load($stub)
    {
        $filename = __DIR__ . '/stubs/' . str_replace('.', '/', $stub);

        $this->stub = $this->files->get($filename . '.stub');
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function fill($data = array())
    {
        foreach ($data as $key => $value)
        {
            $stub = str_replace('{{' . $key  . '}}', $value, $this->stub);
        }

        return $stub;
    }
}