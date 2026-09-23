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
     * Get the placements supervised by this user (faculty coordinator).
     */
    public function supervisedPlacements(): HasMany
    {
        return $this->hasMany(Placement::class, 'supervisor_id');
    }

    /**
     * Get the evaluations submitted by this user.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    /**
     * Role checking helper methods
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator';
    }

    public function isAdminOrCoordinator(): bool
    {
        return in_array($this->role, ['admin', 'coordinator'], true);
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
}
