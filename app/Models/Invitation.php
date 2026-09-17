<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'company_id',
        'invited_by',
        'email',
        'role',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'accepted_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isAccepted();
    }

    public function getAcceptUrl(): string
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'invitations.accept',
            $this->expires_at,
            ['invitation' => $this->id]
        );
    }
}
