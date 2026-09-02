<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use BustsHomeCache;

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

    /**
     * Locale accessors — English only when that column actually has content,
     * otherwise the Arabic original (same rule as PageSection::text()).
     */
    public function getNameAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->name_en) ? $this->name_en : $this->name_ar;
    }

    public function getFocusAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->focus_en) ? $this->focus_en : $this->focus_ar;
    }

    public function getTopicsAttribute(): array
    {
        return app()->getLocale() === 'en' && filled($this->topics_en)
            ? $this->topics_en
            : ($this->topics_ar ?? []);
    }

    public function getOutcomesAttribute(): array
    {
        return app()->getLocale() === 'en' && filled($this->outcomes_en)
            ? $this->outcomes_en
            : ($this->outcomes_ar ?? []);
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
