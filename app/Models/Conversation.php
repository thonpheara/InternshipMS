<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'company_profile_id',
        'application_id',
        'subject',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Check if user is a participant.
     */
    public function isParticipant(User $user): bool
    {
        if ($user->isStudent()) {
            return $this->student_profile_id === $user->studentProfile?->id;
        }

        if ($user->isCompany()) {
            return $this->company_profile_id === $user->companyProfile?->id;
        }

        return $user->isAdmin();
    }

    /**
     * Get unread message count for a specific user.
     */
    public function unreadCountFor(User $user): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get the other participant for the given user.
     */
    public function getRecipientFor(User $user): ?User
    {
        if ($user->isStudent()) {
            return $this->companyProfile?->user;
        }

        if ($user->isCompany()) {
            return $this->studentProfile?->user;
        }

        return null;
    }
}
