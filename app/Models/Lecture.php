<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecture extends Model
{
    use BustsHomeCache;

    protected $fillable = [
        'level_id', 'speaker_id', 'sort_order', 'title_ar', 'title_en',
        'description_ar', 'description_en', 'duration_seconds',
        'video_path', 'is_preview', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_preview' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Locale accessors — English only when that column actually has content,
     * otherwise the Arabic original (same rule as PageSection::text()).
     */
    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->title_en) ? $this->title_en : $this->title_ar;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->description_en)
            ? $this->description_en
            : $this->description_ar;
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getDurationLabelAttribute(): string
    {
        $m = intdiv($this->duration_seconds, 60);
        $s = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $m, $s);
    }
}
