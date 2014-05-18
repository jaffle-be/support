<?php

namespace Jaffle\Support\Database\Eloquent;

use Illuminate\Auth\AuthManager;
use Illuminate\Events\Dispatcher;
use Carbon\Carbon;
use Auth;

class Observer {

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events, AuthManager $auth)
    {
        $this->events = $events;

        $this->auth = $auth;
    }

    /**
     * track all columns for the given field
     * @param $model
     * @param $field
     */
    protected function track($model, $field)
    {
        $this->trackAt($model, $field);
        $this->trackBy($model, $field);
    }

    /**
     * only track '_by' column for the given field
     * @param $model
     * @param $field
     */
    protected function trackBy($model, $field)
    {
        $model->{$field . '_by'} = $this->auth->user() ?
            $this->auth->user()->id :
            null;
    }

    /**
     * only track '_at' column for the given field
     * @param $model
     * @param $field
     */
    protected function trackAt($model, $field)
    {
        $model->{$field . '_at'} = Carbon::create();
    }

} 