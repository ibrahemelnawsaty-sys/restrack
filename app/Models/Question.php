<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'level_id', 'question_ar', 'question_en',
        'options_ar', 'options_en', 'correct_index',
        'explanation_ar', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'options_ar' => 'array',
            'options_en' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
