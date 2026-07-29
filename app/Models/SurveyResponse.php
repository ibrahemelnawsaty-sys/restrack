<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    /** The six rated axes from the owner's deck (note م12), in display order. */
    public const AXES = [
        'content_quality' => 'جودة المحتوى',
        'clarity' => 'وضوح الطرح',
        'speaker_quality' => 'جودة شرح المتحدث',
        'technical_quality' => 'الجودة التقنية للمنصة',
        'ease_of_use' => 'سهولة الاستخدام والوضوح',
        'recommend' => 'هل توصي بهذا البرنامج؟',
    ];

    protected $fillable = [
        'user_id', 'level_id', 'content_quality', 'clarity', 'speaker_quality',
        'technical_quality', 'ease_of_use', 'recommend', 'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
