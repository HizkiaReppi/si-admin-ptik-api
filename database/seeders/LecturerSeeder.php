<?php

namespace Database\Seeders;

use App\Helpers\LecturerHelper;
use App\Models\Lecturer;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $gender = rand(1, 2);

            $nip = LecturerHelper::generateNIP($i, $gender);
            $nidn = LecturerHelper::generateNIDN();
            $name = Factory::create()->firstName($gender == 1 ? 'male' : 'female') . ' ' . Factory::create()->lastName($gender == 1 ? 'male' : 'female');

            $user = User::create([
                'name' => $name,
                'username' => $nidn,
                'email' => strtolower(str_replace(' ', '', $name)) . '@unima.ac.id',
                'password' => bcrypt($nidn),
                'role' => 'lecturer',
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nidn' => $nidn,
                'phone_number' => '08' . sprintf('%09d', $i),
            ]);
        }
    }
}
