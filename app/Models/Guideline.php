<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guideline extends Model
{
    /** The four groups from the owner's deck (slides 9–11), in display order. */
    public const GROUPS = ['saudi', 'reporting', 'ethics', 'publication'];

    protected $fillable = [
        'name_ar', 'name_en', 'group_key', 'note_ar', 'logo', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Arabic label of the group this guideline belongs to. */
    public static function groupLabel(string $key): string
    {
        return match ($key) {
            'saudi' => 'الأنظمة السعودية والمعايير الوطنية',
            'ethics' => 'أخلاقيات البحث العالمية',
            'publication' => 'النشر والنزاهة الأكاديمية',
            default => 'أدلة كتابة الأبحاث الدولية',
        };
    }
}
