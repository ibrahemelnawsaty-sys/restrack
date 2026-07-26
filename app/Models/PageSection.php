<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSection extends Model
{
    protected $fillable = [
        'page', 'section', 'item_key', 'value_ar', 'value_en',
    ];

    protected static function booted(): void
    {
        // Bust the cached copy whenever content changes (admin edits).
        static::saved(fn () => Cache::forget('page_sections'));
        static::deleted(fn () => Cache::forget('page_sections'));
    }

    /**
     * Resolve editable copy with a locale fallback.
     * Usage: PageSection::text('home', 'hero', 'title', 'default')
     */
    public static function text(string $page, string $section, string $key, string $default = ''): string
    {
        $all = Cache::rememberForever('page_sections', function () {
            return static::all()->keyBy(fn ($r) => "{$r->page}.{$r->section}.{$r->item_key}");
        });

        $row = $all->get("{$page}.{$section}.{$key}");
        if (! $row) {
            return $default;
        }

        $locale = app()->getLocale();
        $val = $locale === 'en' ? ($row->value_en ?: $row->value_ar) : ($row->value_ar ?: $row->value_en);

        return $val !== null && $val !== '' ? $val : $default;
    }
}
