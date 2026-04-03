
---
# 🎓 Sistem Penilaian Mahasiswa

Aplikasi berbasis **Laravel** untuk mengelola data mahasiswa, nilai, absensi, dan laporan akademik secara terstruktur dan modern.

---

## ✨ Fitur Utama

### 👨‍🎓 Manajemen Mahasiswa
- Data mahasiswa per kampus & kelas
- Pencarian berdasarkan NIM / Nama
- Status mahasiswa (aktif / nonaktif)

### 🏫 Kampus & Kelas
- Multi kampus (bisa ganti kampus aktif)
- Relasi kelas per kampus
- Filter dinamis berdasarkan kampus

### 📚 Mata Kuliah
- Mata kuliah per kelas
- Jenis:
  - Teori
  - Praktikum
  - Teori + Praktikum

---

## 📊 Sistem Penilaian

### 📝 Nilai Teori
- Keaktifan (20%)
- Tugas (20%)
- UTS (25%)
- UAS (35%)

### 🔧 Nilai Praktikum
- Nilai praktikum (100%)

### 🧮 Perhitungan Nilai Akhir
- Teori → nilai teori
- Praktikum → nilai praktikum
- Gabungan → (Teori 50% + Praktikum 50%)

### 🎯 Syarat Kelulusan
- Kehadiran ≥ **75%**
- Nilai akhir ≥ **55**

---

## 📅 Absensi
- Sistem kehadiran per pertemuan
- Perhitungan:
  - Poin kehadiran
  - Persentase kehadiran
- Otomatis mempengaruhi kelulusan

---

## 📈 Dashboard

Menampilkan:
- Total mahasiswa
- Total mata kuliah
- Total kelas
- Persentase kelulusan
- Distribusi nilai (A–E)
- Rekap per kelas
- Ranking mahasiswa 🏆

---

## 🏆 Ranking Mahasiswa
- Berdasarkan rata-rata nilai akhir
- Top mahasiswa terbaik kampus
- Highlight ranking 1

---

## 📑 Laporan

### 📊 Nilai per Kelas
- Rekap nilai semua mahasiswa
- Rata-rata per mahasiswa
- Export:
  - Excel
  - PDF

### 📄 Transkrip Nilai
- Data lengkap per mahasiswa
- Nilai tiap mata kuliah
- Total SKS & rata-rata
- Export:
  - Excel
  - PDF

### 📈 Rekap Mata Kuliah
- Distribusi nilai huruf
- Statistik kelulusan

---

## 📤 Export Data
- Export ke **Excel**
- Export ke **PDF**
- Loading indicator saat download

---

## ⚙️ Teknologi

- Laravel
- Blade Template
- Bootstrap 5
- MySQL
- Laravel Excel (opsional)
- DomPDF (PDF export)

---

## 🚀 Instalasi

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
composer install
cp .env.example .env
php artisan key:generate


---
## Prasyarat

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js & NPM (opsional, jika pakai Vite)

---

## 1. Buat Proyek Laravel Baru

```bash
composer create-project laravel/laravel sistem-penilaian
cd sistem-penilaian
```

---

## 2. Konfigurasi Database (.env)

Edit file `.env`:

```env
APP_NAME="Sistem Penilaian"
APP_URL=http://localhost:8000
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_penilaian
DB_USERNAME=root
DB_PASSWORD=

# Timezone Indonesia
APP_TIMEZONE=Asia/Makassar
```

Buat database:
```sql
CREATE DATABASE sistem_penilaian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 3. Pasang Autentikasi (Laravel Breeze)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

---

## 4. Salin File Proyek Ini

Salin file-file berikut ke struktur Laravel:

```
database/migrations/          ← semua file dari folder migrations/
app/Models/                   ← Kampus.php, Kelas.php, MataKuliah.php,
                                 Mahasiswa.php, Absensi.php, NilaiModels.php
app/Http/Controllers/         ← DashboardController.php, NilaiController.php,
                                 AbsensiController.php, LaporanController.php,
                                 OtherControllers.php (pisah per class)
database/seeders/             ← DatabaseSeeder.php
routes/                       ← web.php
resources/views/              ← seluruh folder views/
```

> **Catatan:** File `NilaiModels.php` dan `OtherControllers.php` berisi
> beberapa class. Pisahkan menjadi file individual sesuai nama class-nya.

---

## 5. Jalankan Migrasi & Seeder

```bash
php artisan migrate:fresh --seed
```

Output seeder:
```
Seeder berhasil: 2 kampus, 5 kelas, 3 mata kuliah, 8 mahasiswa.
```

---

## 6. Tambahkan Helper Blade `@active`

Di `AppServiceProvider.php` dalam metode `boot()`:

```php
use Illuminate\Support\Facades\Blade;

public function boot(): void
{
    Blade::directive('active', function ($expression) {
        return "<?php echo request()->routeIs($expression) ? 'active' : ''; ?>";
    });
}
```

---

## 7. Jalankan Aplikasi

```bash
php artisan serve
```

Buka: http://localhost:8000

**Login default (dari seeder):**
- Email: `admin@penilaian.ac.id`
- Password: `password`

---

## 8. Paket Opsional

### Export PDF (Laporan)
```bash
composer require barryvdh/laravel-dompdf
```
Aktifkan method `exportPdf()` di `LaporanController.php`.

### Export Excel
```bash
composer require maatwebsite/excel
```

---

## Struktur Database

```
kampus
├── id, nama, kode, alamat, telepon

kelas
├── id, kampus_id*, nama, kode, semester, tahun_ajaran, wali_kelas

mata_kuliah
├── id, kampus_id*, kelas_id*, kode, nama, sks, jenis, dosen, total_pertemuan

mahasiswa
├── id, kampus_id*, kelas_id*, nim, nama, jenis_kelamin, email, status, ...

pendaftaran_mahasiswa  ← pivot many-to-many
├── id, mahasiswa_id*, mata_kuliah_id*, tahun_ajaran, semester, status

absensi
├── id, mahasiswa_id*, mata_kuliah_id*, pertemuan_ke, tanggal
├── status: H(2)/T(1)/S(1)/I(0)/A(0)

nilai_teori
├── id, mahasiswa_id*, mata_kuliah_id*
├── keaktifan(20%), tugas(20%), uts(25%), uas(35%), nilai_akhir_teori

nilai_praktikum
├── id, mahasiswa_id*, mata_kuliah_id*, nilai_praktikum(100%)

nilai_akhir  ← hasil kalkulasi final
├── id, mahasiswa_id*, mata_kuliah_id*
├── nilai_teori, nilai_praktikum, nilai_akhir (50:50)
├── huruf_mutu (A/B/C/D/E), persentase_kehadiran, poin_kehadiran
└── status_kelulusan, keterangan_gagal
```

---

## Logika Perhitungan

### Nilai Teori
```
NA_Teori = (Keaktifan × 0.20) + (Tugas × 0.20) + (UTS × 0.25) + (UAS × 0.35)
```

### Nilai Praktikum
```
NA_Praktikum = nilai_praktikum (100%)
```

### Nilai Akhir (Teori + Praktikum)
```
Jenis "teori"           → NA = NA_Teori
Jenis "praktikum"       → NA = NA_Praktikum
Jenis "teori_praktikum" → NA = (NA_Teori × 0.50) + (NA_Praktikum × 0.50)
```

### Kehadiran
```
Bobot: H=2, T=1, S=1, I=0, A=0
Poin Kehadiran   = Σ bobot status semua pertemuan
Persentase       = (Poin / (total_pertemuan × 2)) × 100
Syarat Lulus     ≥ 75%
```

### Status Kelulusan
```
TIDAK LULUS jika:
  - Persentase kehadiran < 75%, ATAU
  - Nilai akhir < 55 (huruf E)
LULUS jika kedua syarat terpenuhi
```

### Konversi Huruf Mutu
```
≥ 85  → A
75-84 → B
65-74 → C
55-64 → D
< 55  → E
```

---

## Rute Utama

| Method | URL | Nama Route | Fungsi |
|--------|-----|-----------|--------|
| GET | /login | login | Halaman login |
| POST | /login | login | Proses login |
| GET | /dashboard | dashboard | Dashboard |
| GET/POST | /kampus | kampus.* | CRUD Kampus |
| GET/POST | /kelas | kelas.* | CRUD Kelas |
| GET/POST | /mata-kuliah | mata-kuliah.* | CRUD Mata Kuliah |
| GET/POST | /mahasiswa | mahasiswa.* | CRUD Mahasiswa |
| GET | /absensi/{id} | absensi.index | Form absensi |
| POST | /absensi/{id} | absensi.simpan | Simpan absensi |
| GET | /absensi/{id}/rekap | absensi.rekap | Rekap kehadiran |
| GET | /nilai/{id} | nilai.index | Rekap nilai |
| GET/POST | /nilai/{id}/teori | nilai.form-teori | Input nilai teori |
| GET/POST | /nilai/{id}/praktikum | nilai.form-praktikum | Input nilai praktikum |
| GET | /laporan/nilai-per-kelas | laporan.nilai-per-kelas | Laporan per kelas |
| GET | /laporan/rekap-kampus | laporan.rekap-kampus | Rekap kampus |
| GET | /laporan/transkrip | laporan.transkrip | Transkrip mahasiswa |




