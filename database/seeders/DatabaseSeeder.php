<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call RoleSeeder to create roles and permissions first
        $this->call(RoleSeeder::class);

        // Check if admin user already exists before seeding
        if (!User::where('email', 'cosmasasango12@gmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Cosmas Asango',
                'email' => 'cosmasasango12@gmail.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'super-admin',
                'department' => 'administration',
            ]);
            $admin->assignRole('super-admin');
        }
        // Check if admin user already exists before seeding
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'admin',
                'department' => 'administration',
            ]);
            $admin->assignRole('admin');
        }

        // Check if logistics user already exists before seeding
        if (!User::where('email', 'logistics@example.com')->exists()) {
            $logistics = User::factory()->create([
                'name' => 'logistics',
                'email' => 'logistics@example.com',
                'password' => bcrypt('password'),
                'role' => 'logistics',
                'department' => 'logistics',
            ]);
            $logistics->assignRole('logistics');
        }

        // Check if store user already exists before seeding
        if (!User::where('email', 'storemanager@example.com')->exists()) {
            User::create([
                'name' => 'Store Manager',
                'email' => 'storemanager@example.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'store',
                'department' => 'stores',
            ])->assignRole('store');
        }

        // Check if procurement user already exists before seeding
        if (!User::where('email', 'procurementmanager@example.com')->exists()) {
            User::create([
                'name' => 'Procurement Manager',
                'email' => 'procurementmanager@example.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'procurement',
                'department' => 'procurement',
            ])->assignRole('procurement');
        }

        // Check if project manager user already exists before seeding
        if (!User::where('email', 'projectmanager@example.com')->exists()) {
            User::create([
                'name' => 'Project Manager',
                'email' => 'projectmanager@example.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'pm',
                'department' => 'projects',
            ])->assignRole('pm');
        }

        // Check if project officer user already exists before seeding
        if (!User::where('email', 'projectofficer@example.com')->exists()) {
            User::create([
                'name' => 'Project Officer',
                'email' => 'projectofficer@example.com',
                'password' => bcrypt('password'), // Always hash passwords
                'role' => 'po',
                'department' => 'projects',
            ])->assignRole('po');
        }
    }
}
