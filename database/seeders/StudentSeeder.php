<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $gender = rand(1, 2);
            $currentYear = date('Y');
            $year = rand($currentYear - 8, $currentYear);

            $nim = substr($year, -2) . '208' . sprintf('%03d', $i);
            $name = Factory::create()->firstName($gender == 1 ? 'male' : 'female') . ' ' . Factory::create()->lastName($gender == 1 ? 'male' : 'female');

            $user = User::create([
                'name' => $name,
                'username' => $nim,
                'email' => $nim . '@unima.ac.id',
                'password' => bcrypt($nim),
                'role' => 'student',
            ]);

            $supervisor_1 = Lecturer::inRandomOrder()->first();
            $supervisor_2 = rand(0, 1) ? Lecturer::inRandomOrder()->first() : null; 

            Student::factory()->create([
                'user_id' => $user->id,
                'lecturer_id_1' => $supervisor_1->id,
                'lecturer_id_2' => $supervisor_2->id ?? null,
                'nim' => $nim,
                'entry_year' => $year,
                'gender' => $gender == 1 ? 'Male' : 'Female',
            ]);
        }
    }
}
