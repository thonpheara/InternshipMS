<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'internship_post_id',
        'student_profile_id',
        'cover_letter',
        'custom_resume_path',
        'status',
        'company_notes',
        'applied_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function internshipPost(): BelongsTo
    {
        return $this->belongsTo(InternshipPost::class);
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getCustomResumePathAttribute($value): ?string
    {
        return ($value === '0' || $value === '' || $value === null) ? null : $value;
    }

    public function getEffectiveResumePath(): ?string
    {
        return $this->custom_resume_path ?: $this->studentProfile?->resume_path;
    }
}
