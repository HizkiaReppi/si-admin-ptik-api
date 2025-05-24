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

            $degrees = ['S1', 'S2'];
            $hasS3 = rand(0, 1) === 1;
            if ($hasS3) {
                $degrees[] = 'S3';
            }

            $frontDegrees = [];
            $backDegrees = [];

            if ($hasS3) {
                if (rand(0, 4) === 0) {
                    $frontDegrees[] = 'Prof.';
                }
                $frontDegrees[] = 'Dr.';
                $backDegrees[] = 'Ph.D.';
            } else {
                if (rand(0, 1)) {
                    $frontDegrees[] = 'Dr.';
                }
            }

            $s2Degrees = ['M.T.', 'M.Kom.', 'M.Si.', 'M.Pd.', 'M.Cs', 'M.Sc.'];
            $s1Degrees = ['S.T.', 'S.Kom.', 'S.Si.', 'S.Pd.'];

            if (in_array('S2', $degrees)) {
                $backDegrees[] = fake()->randomElement($s2Degrees);
            }

            if (in_array('S1', $degrees)) {
                $backDegrees[] = fake()->randomElement($s1Degrees);
            }

            $frontDegreeStr = implode(' ', array_unique($frontDegrees));
            $backDegreeStr = implode(', ', array_unique($backDegrees));

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
                'front_degree' => $frontDegreeStr,
                'back_degree' => $backDegreeStr,
            ]);

            $lecturer->researchFields()->attach(ResearchField::inRandomOrder()->take(rand(1, 5))->pluck('id'));

            foreach ($degrees as $degree) {
                Education::factory()->create([
                    'lecturer_id' => $lecturer->id,
                    'degree' => $degree,
                ]);
            }

            Experience::factory(rand(2, 3))->create(['lecturer_id' => $lecturer->id]);
        }
    }
}
