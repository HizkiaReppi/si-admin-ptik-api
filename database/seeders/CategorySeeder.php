<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'SK Seminar Proposal',
                'slug' => 'sk-seminar-proposal',
                'docs_file_path' => null,
            ],
            [
                'name' => 'SK Ujian Hasil Penelitian',
                'slug' => 'sk-ujian-hasil-penelitian',
                'docs_file_path' => null,
            ],
            [
                'name' => 'Berita Acara Konversi Nilai MBKM',
                'slug' => 'berita-acara-konversi-nilai-mbkm',
                'docs_file_path' => null,
            ],
            [
                'name' => 'Permohonan Ujian Komprehensif',
                'slug' => 'permohonan-ujian-komprehensif',
                'docs_file_path' => null,
            ],
            [
                'name' => 'Surat Aktif Kuliah',
                'slug' => 'surat-aktif-kuliah',
                'docs_file_path' => null,
            ],
            [
                'name' => 'Ijin Survey',
                'slug' => 'ijin-survey',
                'docs_file_path' => null,
            ],
            [
                'name' => 'PDPT',
                'slug' => 'pdpt',
                'docs_file_path' => null,
            ],
            [
                'name' => 'Permohonan SK Pembimbing Skripsi',
                'slug' => 'permohonan-sk-pembimbing-skripsi',
                'docs_file_path' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
