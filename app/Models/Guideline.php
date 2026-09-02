<?php

namespace App\Models;

use App\Models\Concerns\BustsHomeCache;
use Illuminate\Database\Eloquent\Model;

class Guideline extends Model
{
    use BustsHomeCache;

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

    /**
     * Locale accessor — English only when that column actually has content,
     * otherwise the Arabic original (same rule as PageSection::text()).
     * `note_ar` has no English column, so it stays as-is.
     */
    public function getNameAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->name_en) ? $this->name_en : $this->name_ar;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Label of the group this guideline belongs to, translated via lang/en.json. */
    public static function groupLabel(string $key): string
    {
        return match ($key) {
            'saudi' => __('الأنظمة السعودية والمعايير الوطنية'),
            'ethics' => __('أخلاقيات البحث العالمية'),
            'publication' => __('النشر والنزاهة الأكاديمية'),
            default => __('أدلة كتابة الأبحاث الدولية'),
        };
    }
}
