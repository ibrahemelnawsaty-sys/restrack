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

    /**
     * Locale accessors — English only when that column actually has content,
     * otherwise the Arabic original (same rule as PageSection::text()).
     */
    public function getQuestionAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->question_en) ? $this->question_en : $this->question_ar;
    }

    /**
     * Grading compares the submitted index against correct_index, so the English
     * list may only be used when it lines up one-for-one with the Arabic one.
     * A shorter or longer translation falls back rather than mis-grading silently.
     */
    public function getOptionsAttribute(): array
    {
        $ar = $this->options_ar ?? [];

        return app()->getLocale() === 'en'
            && filled($this->options_en)
            && count($this->options_en) === count($ar)
                ? $this->options_en
                : $ar;
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
