<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
