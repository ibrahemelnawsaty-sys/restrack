<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * The public landing page caches all of its reads under one key (HomeController::index).
 * Any model that feeds that page drops the key when it changes, so admin edits show up at once.
 */
trait BustsHomeCache
{
    public static function bootBustsHomeCache(): void
    {
        static::saved(fn () => Cache::forget('home:data'));
        static::deleted(fn () => Cache::forget('home:data'));
    }
}
