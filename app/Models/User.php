<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_STUDENT = 'student';
    public const ROLE_AMBASSADOR = 'ambassador';   // doctor who only invites students (no teaching)
    public const ROLE_INSTRUCTOR = 'instructor';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';

    /** Role keys → Arabic labels for the admin UI. */
    public static function roleLabels(): array
    {
        return [
            self::ROLE_STUDENT => 'طالب',
            self::ROLE_AMBASSADOR => 'سفير (دعوة فقط)',
            self::ROLE_INSTRUCTOR => 'مدرّب',
            self::ROLE_ADMIN => 'مدير',
            self::ROLE_SUPER_ADMIN => 'مدير عام',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'locale',
        'theme',
        'referrer_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ----- relationships -----

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function examAttempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function lectureProgress(): HasMany
    {
        return $this->hasMany(LectureProgress::class);
    }

    public function speaker(): HasOne
    {
        return $this->hasOne(Speaker::class);
    }

    // ----- roles -----

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isInstructor(): bool
    {
        return $this->role === self::ROLE_INSTRUCTOR;
    }

    public function isAmbassador(): bool
    {
        return $this->role === self::ROLE_AMBASSADOR;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /** The landing route name for this user's role (used after login and in the navbar). */
    public function homeRoute(): string
    {
        return match (true) {
            $this->isAdmin() => 'admin.dashboard',
            $this->isInstructor() => 'instructor.dashboard',
            $this->isAmbassador() => 'ambassador.dashboard',
            default => 'dashboard',
        };
    }

    // ----- subscription gate: one active, non-expired subscription unlocks everything -----

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest('id')
            ->first();
    }

    public function isSubscribed(): bool
    {
        // Admins/instructors always have access to content.
        if ($this->isAdmin() || $this->isInstructor()) {
            return true;
        }

        return $this->activeSubscription() !== null;
    }

    // ----- referrals (a doctor invites students) -----

    /** The referrer (from the directory) this user came through. */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Referrer::class, 'referrer_id');
    }

    /** This user's own directory row (instructors/ambassadors act as referrers). */
    public function referrerProfile(): HasOne
    {
        return $this->hasOne(Referrer::class, 'user_id');
    }

    public function ensureReferrerProfile(): Referrer
    {
        $referrer = $this->referrerProfile()->first()
            ?: $this->referrerProfile()->create(['name' => $this->name, 'is_active' => true]);

        $referrer->ensureCode();

        return $referrer;
    }

    /** Scope: users who currently hold an active, non-expired subscription. */
    public function scopeSubscribedActive($query)
    {
        return $query->whereHas('subscriptions', function ($q) {
            $q->where('status', Subscription::STATUS_ACTIVE)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                });
        });
    }
}
