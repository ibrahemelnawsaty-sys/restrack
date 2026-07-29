<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    public const TYPE_LEVEL = 'level';
    public const TYPE_FINAL = 'final';

    protected $fillable = [
        'user_id', 'level_id', 'type', 'score', 'number', 'verify_uuid', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'score' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $cert) {
            $cert->verify_uuid ??= (string) Str::uuid();
            $cert->number ??= static::generateNumber();
            $cert->issued_at ??= now();
        });
    }

    public static function generateNumber(): string
    {
        // Collision-safe: retry until unique.
        do {
            $number = 'RST-'.now()->year.'-'.strtoupper(Str::random(8));
        } while (static::where('number', $number)->exists());

        return $number;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
