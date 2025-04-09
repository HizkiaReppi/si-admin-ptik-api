<?php

namespace Database\Seeders;

use App\Models\HeadOfDepartment;
use App\Models\Lecturer;
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
        $kajurNIDN = '1234567890';
        $userKajur = User::factory()->create([
            'role' => 'HoD',
            'username' => 'kajur_' . $kajurNIDN,
            'email' => 'kajur_kajur@gmail.com',
            'password' => bcrypt('kajur_' . $kajurNIDN),
        ]);

        $kajur = Lecturer::factory()->create([
            'user_id' => $userKajur->id,
            'nidn' => $kajurNIDN,
            'type' => 'PNS',
        ]);
        
        $kajur = HeadOfDepartment::factory()->create([
            'user_id' => $userKajur->id,
            'lecturer_id' => $kajur->id,
            'role' => 'kajur',
        ]);
        
        $sekjurNIDN = '0987654321';
        $userSekjur = User::factory()->create([
            'role' => 'HoD',
            'username' => 'sekjur_' . $sekjurNIDN,
            'email' => 'sekjur_sekjur@gmail.com',
            'password' => bcrypt('sekjur_' . $sekjurNIDN),
        ]);
        
        $sekjur = Lecturer::factory()->create([
            'user_id' => $userSekjur->id,
            'nidn' => $sekjurNIDN,
            'type' => 'PNS',
        ]);

        $sekjur = HeadOfDepartment::factory()->create([
            'user_id' => $userSekjur->id,
            'lecturer_id' => $sekjur->id,
            'role' => 'sekjur',
        ]);
    }
}
