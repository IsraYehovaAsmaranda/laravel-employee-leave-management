<?php

namespace App;

use App\Models\Scopes\ActiveScope;

trait HasActiveScope
{
    protected static function bootHasActiveScope(){
        static::addGlobalScope(new ActiveScope);
    }
}
