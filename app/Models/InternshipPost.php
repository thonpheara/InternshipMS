<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_profile_id',
        'title',
        'slug',
        'category',
        'description',
        'responsibilities',
        'requirements',
        'location',
        'type',
        'duration_weeks',
        'stipend',
        'is_stipend_disclosed',
        'slots',
        'deadline',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'duration_weeks' => 'integer',
            'slots' => 'integer',
            'stipend' => 'decimal:2',
            'is_stipend_disclosed' => 'boolean',
        ];
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'approved')
            ->whereDate('deadline', '>=', now());
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isOpen(): bool
    {
        return $this->status === 'approved' && $this->deadline >= now()->toDateString();
    }
}
