<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'industry',
        'website',
        'location',
        'address',
        'logo_path',
        'description',
        'contact_person',
        'contact_phone',
        'verification_status',
        'rejection_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function internshipPosts(): HasMany
    {
        return $this->hasMany(InternshipPost::class);
    }

    public function applications(): HasManyThrough
    {
        return $this->hasManyThrough(Application::class, InternshipPost::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
