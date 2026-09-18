<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Placement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_profile_id',
        'company_profile_id',
        'internship_post_id',
        'application_id',
        'supervisor_id',
        'start_date',
        'end_date',
        'total_hours_required',
        'status',
        'completion_remarks',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_hours_required' => 'integer',
        ];
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function internshipPost(): BelongsTo
    {
        return $this->belongsTo(InternshipPost::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function weeklyLogs(): HasMany
    {
        return $this->hasMany(WeeklyLog::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function totalHoursLogged(): float
    {
        return (float) $this->weeklyLogs()->where('status', 'approved')->sum('hours_completed');
    }

    public function completionPercentage(): float
    {
        if ($this->total_hours_required <= 0) {
            return 0.0;
        }

        $logged = $this->totalHoursLogged();
        return min(100.0, round(($logged / $this->total_hours_required) * 100, 1));
    }
}
