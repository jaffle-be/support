<?php

namespace Jaffle\Support\Database\Schema;

use Exception;
use Config;

class Blueprint extends \Illuminate\Database\Schema\Blueprint{

    public function translate($field,$length = 50, $nullable = true)
    {
        $locales = Config::get('app.locales');

        if(!$locales)
        {
            throw new Exception('Locales not found, please add them to your app config file');
        }

        foreach($locales as $locale)
        {
            $column = $this->string($field . '_' . $locale, $length);

            if($nullable)
            {
                $column->nullable();
            }
        }
    }

    public function timestamps()
    {
        $this->track('created', true);
        $this->track('updated', true);
    }

    public function softDeletes()
    {
        $this->track('deleted');
    }

    /**
     * Indicate that the timestamp columns should be dropped.
     *
     * @return void
     */
    public function dropTimestamps()
    {
        $this->untrack('created');
        $this->untrack('updated');
    }

    /**
     * Indicate that the soft delete column should be dropped.
     *
     * @return void
     */
    public function dropSoftDeletes()
    {
        $this->untrack('deleted');
    }
    
    public function track($field, $required = false)
    {
        $column = $this->timestamp($field . "_at");

        if(!$required)
        {
            $column->nullable();
        }
        $this->integer($field . "_by")->unsigned()->nullable();
        $this->foreign($field . "_by")->references('id')->on('users');
    }

    public function untrack($field)
    {
        $this->dropForeign($field . "_by");
        $this->dropColumn($field . "_by");
        $this->dropColumn($field . "_at");
    }
    
} 