<?php

namespace Jaffle\Support\Database\Migrations;

class Migration extends \Illuminate\Database\Migrations\Migration{

    protected $schema;

    public function __construct()
    {
        $this->schema = \DB::connection()->getSchemaBuilder();

        $this->schema->blueprintResolver(function($table, $callback)
        {
            return new \Jaffle\Support\Database\Schema\Blueprint($table, $callback);
        });
    }

} 