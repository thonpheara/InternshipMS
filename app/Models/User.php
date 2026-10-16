<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Get the student profile associated with the user.
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Get the company profile associated with the user.
     */
    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    /**
     * Role checking helper methods
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isCompany(): bool
    {
        return $this->role === 'company';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function unreadMessagesCount(): int
    {
        if ($this->isStudent() && $this->studentProfile) {
            $conversationIds = Conversation::where('student_profile_id', $this->studentProfile->id)->pluck('id');
            return Message::whereIn('conversation_id', $conversationIds)
                ->where('sender_id', '!=', $this->id)
                ->where('is_read', false)
                ->count();
        }

        if ($this->isCompany() && $this->companyProfile) {
            $conversationIds = Conversation::where('company_profile_id', $this->companyProfile->id)->pluck('id');
            return Message::whereIn('conversation_id', $conversationIds)
                ->where('sender_id', '!=', $this->id)
                ->where('is_read', false)
                ->count();
        }

        return 0;
    }

    /**
     * Get the resolved avatar public URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar_path) {
            return asset('storage/' . ltrim($this->avatar_path, '/'));
        }

        if ($this->isCompany() && $this->companyProfile?->logo_path) {
            return asset('storage/' . ltrim($this->companyProfile->logo_path, '/'));
        }

        return null;
    }

    /**
     * Check if user has an uploaded avatar or company logo on disk.
     */
    public function getHasAvatarAttribute(): bool
    {
        $path = $this->avatar_path ?: ($this->isCompany() ? $this->companyProfile?->logo_path : null);
        if (!$path) {
            return false;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path)
            || file_exists(public_path('storage/' . ltrim($path, '/')));
    }

    /**
     * Get 2-letter uppercase initials for avatar badge fallback.
     */
    public function getInitialsAttribute(): string
    {
        $name = $this->display_name;
        $parts = array_filter(explode(' ', trim($name)));
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
        }

        return strtoupper(substr($name ?: 'US', 0, 2));
    }

    /**
     * Get display name (company name for employers, name for students/admins).
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isCompany() && $this->companyProfile?->company_name) {
            return $this->companyProfile->company_name;
        }

        return $this->name;
    }

    /**
     * Get human-readable role badge title.
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin' => 'University Admin',
            'company' => 'Host Company',
            default => 'Student Intern',
        };
    }
}
