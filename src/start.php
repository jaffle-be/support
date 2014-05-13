<?php

namespace Jaffle\Support;

use Jaffle\Support\Exception\FormValidationException;
use App;
use Redirect;

App::error(function(FormValidationException $exception, $code)
{
    return Redirect::back()->withErrors($exception->getErrors())->withInput();
});