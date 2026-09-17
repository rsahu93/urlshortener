<?php

namespace App\Policies;

use App\Models\ShortUrl;
use App\Models\User;

class ShortUrlPolicy
{
   
    public function create(User $user): bool
    {
        return $user->role->canCreateShortUrls();
    }

    public function viewAny(User $user): bool
    {
        return $user->role->belongsToCompany();
    }
    
    public function view(User $user, ShortUrl $shortUrl): bool
    {
        if (! $user->role->belongsToCompany()) {
            return false;
        }

        if ($user->company_id !== $shortUrl->company_id) {
            return false;
        }
        
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $shortUrl->user_id;
    }
}
