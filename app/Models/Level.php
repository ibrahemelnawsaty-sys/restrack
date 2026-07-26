<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'sort_order', 'slug', 'name_ar', 'name_en', 'focus_ar', 'focus_en',
        'topics_ar', 'topics_en', 'outcomes_ar', 'outcomes_en',
        'pass_threshold', 'exam_questions_count', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'topics_ar' => 'array',
            'topics_en' => 'array',
            'outcomes_ar' => 'array',
            'outcomes_en' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class)->orderBy('sort_order');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
