<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Requirement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            switch ($category->name) {
                case 'SK Seminar Proposal':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Draf Proposal',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Transkrip Nilai',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Lembar Persetujuan',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Kartu Peserta Seminar',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'KRS Semester Berjalan',
                    ]);
                    break;

                case 'SK Ujian Hasil Penelitian':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Lembar Persetujuan',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Transkrip Nilai',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'KRS Semester Berjalan',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Draf Skripsi',
                    ]);
                    break;

                case 'Berita Acara Konversi Nilai MBKM':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Surat Tugas',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Laporan Akhir',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'File Berita Acara',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'KRS Sementara',
                    ]);
                    break;

                case 'Permohonan Ujian Komprehensif':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT Semester 1 s/d Semester Akhir',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Fotocopy Ijazah Terakhir (Legalisir)',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Bukti Published Jurnal',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Pas Foto 3x4',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Surat Keterangan Bebas Perpustakaan',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Biodata',
                    ]);
                    break;

                case 'Surat Aktif Kuliah':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT Terbaru',
                    ]);
                    break;

                case 'Ijin Survey':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'SK Pembimbing Akademik 2',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT Terbaru',
                    ]);
                    break;

                case 'PDPT':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT Terbaru',
                    ]);
                    break;

                case 'Permohonan SK Pembimbing Skripsi':
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Berita Acara Ujian Proposal',
                    ]);
                    Requirement::create([
                        'category_id' => $category->id,
                        'name' => 'Slip UKT Terbaru',
                    ]);
                    break;
            }
        }
    }
}
