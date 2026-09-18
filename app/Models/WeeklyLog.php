<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'placement_id',
        'week_number',
        'start_date',
        'end_date',
        'hours_completed',
        'tasks_summary',
        'learnings_challenges',
        'status',
        'company_feedback',
        'supervisor_feedback',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'hours_completed' => 'decimal:2',
            'approved_at' => 'datetime',
            'week_number' => 'integer',
        ];
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
