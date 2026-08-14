<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Super Admin user with full admin permissions
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Ensure default company profile is associated with the super admin
        Company::firstOrCreate(
            [
                'user_id' => $admin->id,
                'name' => 'Default Company'
            ]
        );

        $this->command->info("Super Admin user successfully created/updated:");
        $this->command->info("Email: admin@admin.com");
        $this->command->info("Password: admin123");
        $this->command->info("Permissions: Full Administrator (is_admin = true)");
    }
}
