<?php

namespace Database\Seeders;

use App\Helpers\LecturerHelper;
use App\Models\Lecturer;
use App\Models\Lecturers\Education;
use App\Models\Lecturers\Experience;
use App\Models\Lecturers\ResearchField;
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
                'gender' => $gender == 1 ? 'Male' : 'Female',
            ]);

            $lecturer = Lecturer::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nidn' => $nidn,
                'phone_number' => '08' . sprintf('%09d', $i),
            ]);

            // Assign 1-5 random research fields
            $lecturer->researchFields()->attach(
                ResearchField::inRandomOrder()->take(rand(1, 5))->pluck('id')
            );

            // Assign S1, S2 (some with S3) educations
            $degrees = ['S1', 'S2'];
            if (rand(0, 1)) {
                $degrees[] = 'S3';
            }
            foreach ($degrees as $degree) {
                Education::factory()->create([
                    'lecturer_id' => $lecturer->id,
                    'degree' => $degree,
                ]);
            }

            // Assign 2-3 experiences
            Experience::factory(rand(2, 3))->create(['lecturer_id' => $lecturer->id]);
        }
    }
}
