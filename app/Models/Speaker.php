<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use BustsHomeCache;

    protected $fillable = [
        'user_id', 'name_ar', 'name_en', 'title_ar', 'title_en',
        'credential_ar', 'credential_en', 'highlight_ar', 'highlight_en',
        'bio_ar', 'bio_en', 'avatar', 'sort_order', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
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

    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->title_en) ? $this->title_en : $this->title_ar;
    }

    public function getCredentialAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->credential_en)
            ? $this->credential_en
            : $this->credential_ar;
    }

    public function getHighlightAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->highlight_en)
            ? $this->highlight_en
            : $this->highlight_ar;
    }

    public function getBioAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->bio_en) ? $this->bio_en : $this->bio_ar;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Initials fallback for the avatar until the owner supplies photos. */
    public function initials(): string
    {
        $name = trim(preg_replace('/^(د\.|أ\.د\.|Dr\.)\s*/u', '', $this->name ?? ''));
        $parts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return mb_substr($parts[0] ?? '؟', 0, 1).(isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }
}
