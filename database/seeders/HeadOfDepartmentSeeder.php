<?php

namespace Database\Seeders;

use App\Models\HeadOfDepartment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadOfDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'role' => 'HoD'
        ]);

        HeadOfDepartment::factory()->create([
            'user_id' => $user->id
        ]);
    }
}
