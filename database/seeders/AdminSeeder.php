<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => "Super Admin",
            'username' => "superadmin",
            'email' => "superadmin@gmail.com",
            'role' => 'super-admin',
            'password' => bcrypt("superadmin"),
        ]);

        User::factory()->create([
            'id' => Str::uuid(),
            'name' => "Admin",
            'username' => "admin",
            'email' => "admin@gmail.com",
            'role' => 'admin',
            'password' => bcrypt("admin"),
        ]);
    }
}
