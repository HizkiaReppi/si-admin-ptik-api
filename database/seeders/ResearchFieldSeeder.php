<?php

namespace Database\Seeders;

use App\Models\Lecturers\ResearchField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResearchFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Teknologi Informasi dan Komunikasi',
            'description' => 'Teknologi Informasi dan Komunikasi (TIK) mencakup perangkat keras, perangkat lunak, jaringan, dan sistem yang digunakan untuk mengelola informasi dan mendukung komunikasi. TIK memainkan peran penting dalam mempercepat transformasi digital di berbagai sektor, termasuk pendidikan, bisnis, dan pemerintahan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Management in Academic',
            'description' => 'Management in Academic merupakan bidang yang mempelajari prinsip, strategi, dan praktik manajemen yang diterapkan untuk meningkatkan efektivitas operasional dan kualitas pendidikan di institusi akademik.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Information System',
            'description' => 'Sistem Informasi adalah bidang yang berfokus pada desain, pengembangan, implementasi, dan pengelolaan sistem yang mendukung kebutuhan informasi organisasi untuk pengambilan keputusan yang efektif.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Manajemen Pendidikan Teknik',
            'description' => 'Manajemen Pendidikan Teknik mempelajari perencanaan, pengorganisasian, pelaksanaan, dan evaluasi program pendidikan teknik, dengan tujuan meningkatkan kompetensi teknis dan profesional siswa di bidang teknik.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Electricity Energy',
            'description' => 'Bidang ini mempelajari berbagai aspek terkait energi listrik, termasuk pembangkitan, distribusi, konsumsi, dan inovasi teknologi untuk memastikan ketersediaan energi yang efisien dan ramah lingkungan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Entrepreneurship Education',
            'description' => 'Pendidikan Kewirausahaan bertujuan untuk memberikan pengetahuan dan keterampilan kepada individu untuk memulai, mengelola, dan mengembangkan usaha secara inovatif dan berkelanjutan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Pendidikan Kejuruan',
            'description' => 'Pendidikan Kejuruan berfokus pada pengembangan keterampilan teknis dan vokasional yang relevan dengan kebutuhan pasar kerja, untuk mendukung daya saing individu dalam dunia kerja.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Knowledge Bases System',
            'description' => 'Sistem Berbasis Pengetahuan adalah sistem komputer yang dirancang untuk menyimpan, mengelola, dan memanfaatkan pengetahuan manusia untuk mendukung pengambilan keputusan atau menyelesaikan masalah secara otomatis.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'E-Learning',
            'description' => 'E-Learning mengacu pada penggunaan teknologi digital untuk menyediakan pembelajaran yang fleksibel, interaktif, dan dapat diakses kapan saja dan di mana saja.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Business Intelligence',
            'description' => 'Business Intelligence adalah proses pengumpulan, analisis, dan visualisasi data untuk memberikan wawasan yang mendukung pengambilan keputusan strategis dalam organisasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'TI/IS, Mobile Learning',
            'description' => 'TI/IS Mobile Learning adalah pendekatan pembelajaran berbasis teknologi informasi dan sistem informasi yang memanfaatkan perangkat mobile untuk meningkatkan aksesibilitas dan fleksibilitas pembelajaran.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Analisis dan Perancangan Sistem',
            'description' => 'Analisis dan Perancangan Sistem adalah proses yang bertujuan untuk memahami kebutuhan sistem dan merancang solusi teknologi yang efektif untuk memenuhi kebutuhan tersebut.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Teknik Informatika',
            'description' => 'Teknik Informatika adalah bidang ilmu yang mempelajari teknik pemrograman, pengembangan perangkat lunak, algoritma, dan implementasi teknologi untuk menyelesaikan berbagai masalah komputasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'IT/E-Governance',
            'description' => 'IT/E-Governance adalah penerapan teknologi informasi untuk meningkatkan efisiensi, transparansi, dan partisipasi dalam proses tata kelola pemerintahan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'ITS Management',
            'description' => 'ITS Management mencakup pengelolaan sistem transportasi pintar yang berbasis teknologi informasi untuk meningkatkan efisiensi, keamanan, dan keberlanjutan transportasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Information Security',
            'description' => 'Keamanan Informasi adalah bidang yang fokus pada perlindungan data dan sistem informasi dari ancaman seperti akses tidak sah, gangguan, atau kebocoran informasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Design and System Software Engineering',
            'description' => 'Rekayasa Perangkat Lunak dan Desain Sistem melibatkan perancangan, pengembangan, pengujian, dan pemeliharaan perangkat lunak dengan pendekatan yang sistematis dan terstruktur.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Manajemen Pendidikan',
            'description' => 'Manajemen Pendidikan adalah bidang studi yang mempelajari pengelolaan sumber daya manusia, keuangan, dan material dalam institusi pendidikan untuk mencapai tujuan pendidikan yang optimal.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Analisis Sistem',
            'description' => 'Analisis Sistem adalah proses mempelajari kebutuhan sistem dan mengevaluasi fungsionalitas sistem yang ada untuk mengidentifikasi perbaikan atau solusi baru.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Jaringan Komputer',
            'description' => 'Jaringan Komputer adalah bidang yang mempelajari desain, implementasi, dan pengelolaan sistem komunikasi data antara perangkat komputer.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Sistem Informasi',
            'description' => 'Sistem Informasi adalah kombinasi teknologi, orang, dan proses yang digunakan untuk mengelola informasi dalam organisasi secara efektif.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Data Model',
            'description' => 'Pemodelan Data adalah proses membuat representasi logis dari data dalam sistem informasi, yang melibatkan struktur data, hubungan, dan aturan bisnis.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Informatics Technology',
            'description' => 'Teknologi Informatika mencakup pengembangan dan penerapan teknologi yang berfokus pada pengolahan dan manajemen informasi untuk mendukung berbagai kebutuhan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Teknologi Pendidikan',
            'description' => 'Teknologi Pendidikan adalah penggunaan teknologi untuk mendukung proses belajar mengajar, termasuk alat bantu pembelajaran, aplikasi pendidikan, dan metode digital.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Computer Science',
            'description' => 'Ilmu Komputer adalah bidang yang mempelajari teori, pengembangan, dan penerapan sistem komputer serta algoritma untuk menyelesaikan masalah komputasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'E-Learning Development',
            'description' => 'Pengembangan E-Learning mencakup proses desain, implementasi, dan evaluasi platform pembelajaran digital untuk meningkatkan pengalaman belajar siswa.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Multimedia',
            'description' => 'Multimedia adalah integrasi teks, gambar, suara, animasi, dan video untuk menyampaikan informasi atau menciptakan pengalaman interaktif.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Game Development',
            'description' => 'Pengembangan Game adalah proses pembuatan permainan digital yang melibatkan desain, pengkodean, pengujian, dan implementasi pengalaman permainan yang interaktif.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Animasi',
            'description' => 'Animasi adalah seni membuat gambar bergerak yang digunakan dalam berbagai aplikasi, termasuk hiburan, pendidikan, dan pemasaran.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Photography',
            'description' => 'Fotografi adalah seni dan ilmu menangkap gambar menggunakan kamera, baik untuk keperluan dokumentasi, seni, maupun komunikasi visual.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Sinematografi',
            'description' => 'Sinematografi adalah seni dan teknik pengambilan gambar bergerak untuk menciptakan narasi visual dalam produksi film atau video.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Teknologi Informasi',
            'description' => 'Teknologi Informasi mencakup perangkat keras, perangkat lunak, dan infrastruktur yang digunakan untuk mengelola informasi secara efektif dan efisien.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Komputer dan Jaringan',
            'description' => 'Komputer dan Jaringan adalah bidang yang mempelajari bagaimana komputer saling terhubung untuk berbagi sumber daya dan informasi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Digital Image Processing',
            'description' => 'Pemrosesan Citra Digital adalah teknik untuk menganalisis, memodifikasi, dan meningkatkan citra digital untuk aplikasi seperti pengenalan wajah, penginderaan jarak jauh, dan lain-lain.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Database System',
            'description' => 'Sistem Basis Data adalah sistem yang digunakan untuk menyimpan, mengelola, dan mengambil data secara efisien menggunakan perangkat lunak khusus.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Software Engineering',
            'description' => 'Rekayasa Perangkat Lunak adalah disiplin ilmu yang fokus pada desain, pengembangan, pengujian, dan pemeliharaan perangkat lunak dengan pendekatan yang terstruktur.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Media dan Teknologi Informasi',
            'description' => 'Media dan Teknologi Informasi menggabungkan penggunaan media digital dan teknologi informasi untuk menyampaikan pesan atau informasi secara efektif.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Education Science',
            'description' => 'Ilmu Pendidikan mempelajari prinsip-prinsip pembelajaran, pengajaran, dan pengelolaan pendidikan untuk meningkatkan hasil belajar.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Informatics Engineering',
            'description' => 'Teknik Informatika adalah bidang studi yang mencakup pengembangan algoritma, perangkat lunak, dan sistem informasi untuk berbagai kebutuhan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Constructivism & Cognitive Learning',
            'description' => 'Pendekatan konstruktivisme dan pembelajaran kognitif menekankan peran aktif siswa dalam membangun pengetahuan mereka melalui pengalaman langsung dan refleksi.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Multimedia Laboratory',
            'description' => 'Laboratorium Multimedia adalah fasilitas yang digunakan untuk pengembangan dan eksplorasi aplikasi multimedia, seperti video, animasi, dan presentasi digital.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Mobile Video Learning',
            'description' => 'Mobile Video Learning adalah pendekatan pembelajaran menggunakan video yang diakses melalui perangkat mobile untuk meningkatkan fleksibilitas dan keterlibatan belajar.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Class Action Research',
            'description' => 'Penelitian Tindakan Kelas adalah metode penelitian yang digunakan oleh guru untuk meningkatkan praktik pengajaran melalui refleksi dan perbaikan berkelanjutan.',
        ]);

        ResearchField::create([
            'id' => Str::uuid(),
            'field_name' => 'Competitive Intelligence',
            'description' => 'Competitive Intelligence adalah proses pengumpulan dan analisis informasi tentang pesaing untuk membantu organisasi membuat keputusan strategis yang lebih baik.',
        ]);
    }
}
