<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'SuperAdmin';
    case Admin = 'Admin';
    case Member = 'Member';
    case Sales = 'Sales';
    case Manager = 'Manager';

    /**
     * Roles that are scoped to (belong to) exactly one company.
     * SuperAdmin is the only role that spans companies.
     */
    public function belongsToCompany(): bool
    {
        return $this !== self::SuperAdmin;
    }

    /**
     * Only Sales and Manager may create short urls.
     * Admin, Member, and SuperAdmin explicitly cannot, per spec.
     */
    public function canCreateShortUrls(): bool
    {
        return in_array($this, [self::Sales, self::Manager], true);
    }
}
