<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use BustsHomeCache;

    protected $fillable = [
        'question_ar', 'question_en', 'answer_ar', 'answer_en',
        'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }
}
