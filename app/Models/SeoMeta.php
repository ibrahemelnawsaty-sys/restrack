<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'route', 'title_ar', 'title_en', 'description_ar', 'description_en',
        'og_image', 'noindex',
    ];

    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Same rule as PageSection: the table is read on every page, so it is cached
        // until an admin edit changes it.
        static::saved(fn () => Cache::forget('seo_meta'));
        static::deleted(fn () => Cache::forget('seo_meta'));
    }

    /**
     * The share card for a route, as the absolute URL Open Graph requires.
     *
     * `og_image` is either a full URL or a path on the `public` disk (the same
     * convention as `guidelines.logo`). Returns null when no row overrides the
     * default, so the caller can fall back to the static card in public/.
     */
    public static function ogImage(?string $route): ?string
    {
        if ($route === null) {
            return null;
        }

        $all = Cache::rememberForever('seo_meta', fn () => static::all()->keyBy('route'));
        $image = $all->get($route)?->og_image;

        if (! filled($image)) {
            return null;
        }

        return str_starts_with($image, 'http') ? $image : asset('storage/'.ltrim($image, '/'));
    }
}
