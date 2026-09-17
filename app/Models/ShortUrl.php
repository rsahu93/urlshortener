<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    use HasFactory;

    /**
     * Short urls only track created_at (per spec) — there is no updated_at column.
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'original_url',
        'short_code',
    ];

    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            return $query->where('company_id', $user->company_id);
        }

        return $query->where('user_id', $user->id);
    }
}
