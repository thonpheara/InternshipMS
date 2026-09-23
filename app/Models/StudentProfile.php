<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id_number',
        'department',
        'major',
        'cohort_year',
        'gpa',
        'resume_path',
        'phone',
        'skills',
        'bio',
        'eligibility_status',
        'eligibility_notes',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'gpa' => 'decimal:2',
            'cohort_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function activePlacement(): HasOne
    {
        return $this->hasOne(Placement::class)->where('status', 'active');
    }

    public function weeklyLogs(): HasManyThrough
    {
        return $this->hasManyThrough(WeeklyLog::class, Placement::class);
    }

    public function evaluations(): HasManyThrough
    {
        return $this->hasManyThrough(Evaluation::class, Placement::class);
    }

    public function isEligible(): bool
    {
        return true;
    }

    public function getEligibilityStatusAttribute($value): string
    {
        return $value === 'pending' || empty($value) ? 'eligible' : $value;
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
