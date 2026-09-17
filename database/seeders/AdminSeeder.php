<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed a sample Company Admin and Sales user for local testing.
     */
    public function run(): void
    {
        $company = Company::firstOrCreate(['name' => 'Acme Corp']);

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Acme Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'sales@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Acme Sales',
                'password' => Hash::make('password'),
                'role' => 'Sales',
                'email_verified_at' => now(),
            ]
        );
    }
}
