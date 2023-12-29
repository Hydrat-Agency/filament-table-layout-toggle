<?php

namespace Hydrat\TableLayoutToggle\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hydrat\TableLayoutToggle\TableLayoutToggle
 */
class TableLayoutToggle extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Hydrat\TableLayoutToggle\TableLayoutToggle::class;
    }
}
