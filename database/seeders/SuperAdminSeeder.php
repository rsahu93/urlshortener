<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * The seeded SuperAdmin's credentials.
     *
     * These are intentionally simple, well-known local-dev credentials.
     * They are documented in the README. Do not use this seeder as-is
     * against a production database.
     */
    public const EMAIL = 'superadmin@example.com';

    public const PASSWORD = 'password';

    /**
     * Seed a single SuperAdmin user using raw SQL, per the assignment's
     * explicit requirement — not Eloquent's User::create().
     *
     * SuperAdmin has no company (company_id is NULL), per the users
     * migration's nullable company_id column.
     */
    public function run(): void
    {
        // Idempotent: skip if a SuperAdmin with this email already exists,
        // so re-running `db:seed` doesn't throw a duplicate-email error.
        $exists = DB::selectOne(
            'SELECT id FROM users WHERE email = ? LIMIT 1',
            [self::EMAIL]
        );

        if ($exists) {
            return;
        }

        DB::statement(
            'INSERT INTO users (company_id, name, email, email_verified_at, password, role, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                null,
                'Super Admin',
                self::EMAIL,
                now(),
                Hash::make(self::PASSWORD),
                'SuperAdmin',
                now(),
                now(),
            ]
        );
    }
}
