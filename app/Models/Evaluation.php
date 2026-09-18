<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'placement_id',
        'evaluator_id',
        'type',
        'performance_rating',
        'technical_skills_rating',
        'soft_skills_rating',
        'attendance_punctuality_rating',
        'comments',
        'recommendation',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'performance_rating' => 'integer',
            'technical_skills_rating' => 'integer',
            'soft_skills_rating' => 'integer',
            'attendance_punctuality_rating' => 'integer',
        ];
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
