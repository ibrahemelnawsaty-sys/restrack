<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use BustsHomeCache;

    protected $fillable = [
        'slug', 'name_ar', 'name_en', 'price', 'interval',
        'features_ar', 'features_en', 'is_active', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features_ar' => 'array',
            'features_en' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
