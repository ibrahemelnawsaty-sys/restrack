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

    /**
     * Locale accessors — English only when that column actually has content,
     * otherwise the Arabic original (same rule as PageSection::text()).
     */
    public function getQuestionAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->question_en)
            ? $this->question_en
            : $this->question_ar;
    }

    public function getAnswerAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->answer_en)
            ? $this->answer_en
            : $this->answer_ar;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }
}
