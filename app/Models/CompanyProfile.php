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

    protected $appends = [
        'logo_url',
        'has_logo',
        'initials',
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

    /**
     * Get resolved logo public URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . ltrim($this->logo_path, '/'));
        }

        return null;
    }

    /**
     * Check if company has an uploaded logo file on disk.
     */
    public function getHasLogoAttribute(): bool
    {
        if (!$this->logo_path) {
            return false;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->logo_path)
            || file_exists(public_path('storage/' . ltrim($this->logo_path, '/')));
    }

    /**
     * Get 2-letter uppercase initials for company fallback avatar.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->company_name ?: ($this->user?->name ?: 'CO');
        $parts = array_filter(explode(' ', trim($name)));
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
        }

        return strtoupper(substr($name, 0, 2));
    }
}
