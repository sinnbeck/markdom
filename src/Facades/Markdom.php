<?php

namespace Sinnbeck\Markdom\Facades;

use Illuminate\Support\Facades\Facade;

class Markdom extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'markdom';
    }
}