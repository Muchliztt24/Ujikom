<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $uploaderRole = Role::firstOrCreate(['name' => 'uploader']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        User::create([
            'name' => 'Admin Nokomi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Uploader Asep',
            'email' => 'Asep@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $uploaderRole->id,
        ]);

        User::create([
            'name' => 'Allay User',
            'email' => 'Allay@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);
    }
}
