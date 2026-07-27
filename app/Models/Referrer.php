<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Referrer extends Model
{
    protected $fillable = ['name', 'referral_code', 'user_id', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /** The account behind this referrer (instructor/ambassador), if any — name-only doctors have none. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referredUsers(): HasMany
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    public function ensureCode(): string
    {
        if (! $this->referral_code) {
            do {
                $code = 'DR'.strtoupper(Str::random(6));
            } while (static::where('referral_code', $code)->exists());

            $this->forceFill(['referral_code' => $code])->save();
        }

        return $this->referral_code;
    }

    public function referralUrl(): string
    {
        return url('/r/'.$this->ensureCode());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
