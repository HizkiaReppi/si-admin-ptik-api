# Sistem Informasi Admin PTIK

Sistem Informasi Admin PTIK adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan administrasi di Jurusan Pendidikan Teknologi Informasi dan Komunikasi, Fakultas Teknik, Universitas Negeri Manado.

# Deployment

Aplikasi ini dapat diakses melalui URL berikut:

-   [Frontend 1](https://si-admin-ptik.laboratoriumweb.id/)
-   [Frontend 2](https://si-admin-ptik.vercel.app/)
-   [Backend](https://api.si-admin-ptik-api.laboratoriumweb.id/)

## Fitur Utama

-   **Manajemen Pengguna:**
    -   Registrasi, login, dan logout dengan autentikasi berbasis token.
    -   Role-based access control untuk membedakan hak akses pengguna.
-   **Manajemen Data Pengguna:**
    -   Pengelolaan data mahasiswa, staf administrasi, dosen, dan pimpinan jurusan.
-   **Manajemen Pengajuan Berkas:**
    -   Pengajuan berkas oleh mahasiswa.
    -   Verifikasi dan validasi berkas oleh staf administrasi.
    -   Pembuatan surat permohonan oleh staff administrasi.
    -   Persetujuan berkas pimpinan jurusan.
-   **Dashboard:**
    -   Statistik penggunaan sistem.
    -   Informasi terbaru terkait administrasi.
-   **Notifikasi:**
    -   Pengingat tugas dan pemberitahuan status berkas.

## Teknologi yang Digunakan

### Frontend

-   **Framework:** React
-   **Styling:** Tailwind CSS
-   **Routing:** React Router

### Backend

-   **Framework:** Laravel
-   **Database:** MySQL
-   **Autentikasi:** Laravel Sanctum

## Instalasi dan Penggunaan

### Prasyarat

Pastikan Anda memiliki:

-   **Node.js** (20 atau lebih baru) dan **npm**
-   **PHP** (v8.2 atau lebih baru)
-   **Composer**
-   **MySQL**

### Langkah Instalasi

#### 1. Clone Repository

```bash
# Clone frontend dan backend
git clone https://github.com/HizkiaReppi/si-admin-ptik.git
cd si-admin-ptik

git clone https://github.com/HizkiaReppi/si-admin-ptik-api.git
cd si-admin-ptik-api
```

#### 2. Setup Backend

```bash
# Masuk ke folder backend
cd si-admin-ptik-api

# Install dependencies
composer install

# Copy .env dan sesuaikan konfigurasi
cp .env.example .env

# Generate application key
php artisan key:generate

# Migrasi database
php artisan migrate

# Jalankan server lokal
php artisan serve
```

#### 3. Setup Frontend

```bash
# Masuk ke folder frontend
cd si-admin-ptik

# Install dependencies
npm install

# Jalankan server lokal
npm run dev
```

## Kontribusi

Kami menyambut kontribusi dari siapa pun. Harap ikuti langkah-langkah berikut untuk berkontribusi:

1. Fork repository.
2. Buat branch baru untuk fitur atau perbaikan Anda.
3. Kirim pull request.

## Tim Pengembang

-   **Nama Pengembang**: Hizkia Reppi
-   **Kontak**: hizkiareppi@gmail.com

## Catatan Tambahan

-   **Dikelola oleh**: Tim Sistem Informasi PTIK
