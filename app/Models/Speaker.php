<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Initials fallback for the avatar until the owner supplies photos. */
    public function initials(): string
    {
        $name = trim(preg_replace('/^(د\.|أ\.د\.|Dr\.)\s*/u', '', $this->name_ar ?? ''));
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
