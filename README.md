# 🎓 Nilai-Mhs: Sistem Penilaian & Akademik Terpadu

Aplikasi berbasis **Laravel** untuk mengelola data akademik secara komprehensif, mulai dari data mahasiswa, absensi harian, hingga kalkulasi otomatis nilai akhir dan pencetakan laporan.

---

## 🚀 Sekilas Aplikasi
**Nilai-Mhs** dirancang untuk memudahkan dosen dan pengelola kampus dalam:
- Mengelola data master (Kampus, Kelas, Mata Kuliah, Mahasiswa).
- Menginput absensi harian secara efisien dengan riwayat dan persentase kehadiran per pertemuan.
- Menghitung nilai mahasiswa menggunakan **standar bobot baku**: **Kehadiran 25%**, **Tugas 20%**, **UTS 25%**, dan **UAS 30%**.
- Menerapkan **aturan nilai minimum berdasarkan persentase kehadiran** serta penetapan alasan ketidaklulusan otomatis.
- Mengelola penilaian praktikum dan kombinasi teori + praktikum.
- Sinkronisasi otomatis antara absensi dan nilai akhir mahasiswa via `PenilaianService`.
- Mengekspor data dalam format PDF dan Excel.

---

## 📊 Logika & Aturan Sistem Penilaian (Revisi Terbaru)

Sistem menggunakan logika penilaian terpusat yang konsisten di seluruh modul (`PenilaianService`):

### 1. Bobot Penilaian Final (Total 100%)
| Komponen | Bobot | Keterangan |
| :--- | :---: | :--- |
| **Kehadiran** | **25%** | Menggunakan persentase kehadiran aktual riil |
| **Tugas Terstruktur & Mandiri** | **20%** | Rata-rata tugas mahasiswa |
| **Tugas Mandiri (UTS)** | **25%** | Nilai Ujian Tengah Semester |
| **Tugas Mandiri (UAS)** | **30%** | Nilai Ujian Akhir Semester |

$$\text{Nilai Akademik} = (\text{Kehadiran} \times 0.25) + (\text{Tugas} \times 0.20) + (\text{UTS} \times 0.25) + (\text{UAS} \times 0.30)$$

> **Catatan:** Komponen keaktifan tidak dijadikan bobot terpisah (bobot 0% pada NA). Seluruh reward/bonus kehadiran lama ($+5, +3, +1$) dinonaktifkan sepenuhnya.

### 2. Nilai Minimum Berdasarkan Kehadiran
Setelah nilai akademik dihitung, diberlakukan ambang nilai minimum berdasarkan persentase kehadiran riil:

| Persentase Kehadiran | Nilai Minimum |
| :---: | :---: |
| **$\ge 95\%$** | **85** (A) |
| **$90 - 94.99\%$** | **75** (B) |
| **$80 - 89.99\%$** | **65** (C) |
| **$75 - 79.99\%$** | **55** (D) |
| **$< 75\%$** | **Tidak Lulus** (tanpa batas minimum) |

* Untuk mahasiswa dengan kehadiran $\ge 75\%$:  
  $$\text{Nilai Akhir} = \max(\text{Nilai Akademik}, \text{Nilai Minimum Kehadiran})$$
  *(Nilai akademik yang lebih tinggi dari batas minimum tidak akan diturunkan).*
* Untuk mahasiswa dengan kehadiran $< 75\%$:  
  $$\text{Nilai Akhir} = \text{Nilai Akademik}$$
  *(Nilai asli dan huruf mutu tetap dipertahankan sesuai capaian angka, tidak dinolkan).*
* Nilai akhir selalu dibatasi pada rentang $0 \le \text{Nilai Akhir} \le 100$.

### 3. Konversi Huruf Mutu
Dilakukan setelah Nilai Akhir final diperoleh:
* **$\ge 85$** $\rightarrow$ **A**
* **$75 - 84.99$** $\rightarrow$ **B**
* **$65 - 74.99$** $\rightarrow$ **C**
* **$55 - 64.99$** $\rightarrow$ **D**
* **$< 55$** $\rightarrow$ **E**

### 4. Syarat & Alasan Ketidaklulusan
Mahasiswa dinyatakan **LULUS** hanya jika:
$$\text{Kehadiran} \ge 75\% \quad \text{DAN} \quad \text{Nilai Akhir} \ge 55$$

Jika mahasiswa tidak lulus, sistem secara otomatis mencatat alasan ketidaklulusan:
* **Kehadiran $< 75\%$**: `"Kehadiran 74% (minimal 75%)"`
* **Nilai Akhir $< 55$**: `"Nilai akhir 50 (minimal 55)"`
* **Keduanya tidak terpenuhi**: `"Kehadiran 70% (minimal 75%); Nilai akhir 50 (minimal 55)"`
* **Lulus**: `null` (tidak ada catatan gagal)

### 5. Kombinasi Teori & Praktikum
* **Mata Kuliah Teori**: $\text{Nilai Akademik} = \text{NA Teori}$.
* **Mata Kuliah Praktikum**: $\text{Nilai Akademik} = \text{Nilai Praktikum}$.
* **Mata Kuliah Teori + Praktikum**:  
  $$\text{Nilai Akademik Final} = (\text{NA Teori} \times 0.5) + (\text{Nilai Praktikum} \times 0.5)$$
  Aturan minimum kehadiran diterapkan pada Nilai Akademik Final (tidak diterapkan dua kali).

---

## 📸 Tampilan Aplikasi (Screenshots)

Berikut adalah beberapa pratinjau antarmuka aplikasi:

### 1. Halaman Login
![Login](ss/login.png)
*Halaman autentikasi sistem.*

### 2. Dashboard Utama
![Dashboard](ss/dashboard.png)
*Gambaran statistik kampus, jumlah mahasiswa, dan tingkat kelulusan.*

### 3. Manajemen Master Data
![Daftar Kelas](ss/daftar%20kelas.png)
*Halaman manajemen kelas perkuliahan.*

![Daftar Mahasiswa](ss/daftar%20mahasiswa.png)
*Halaman manajemen daftar mahasiswa per kampus.*

### 4. Form Input Absensi Harian
![Absensi](ss/absensi.png)
*Tampilan form absensi per pertemuan.*

### 5. Modul Penilaian
![Checklist Keaktifan](ss/input%20nilai%20keaktifan.png)
*Form Keaktifan dengan sistem Checklist interaktif.*

![Input Tugas](ss/input%20nilai%20tugas.png)
*Penambahan dan pengisian kolom tugas secara dinamis.*

![Input Teori](ss/input%20teori.png)
*Form untuk menginput nilai ujian (UTS/UAS).*

### 6. Rekapitulasi & Laporan
![Rekap Nilai](ss/rekap%20nilai%20matakuliah.png)
*Tabel rekapitulasi nilai akhir mahasiswa dan kehadiran.*

![Rekap Mata Kuliah](ss/rekap%20matakuliah.png)
*Statistik nilai dan distribusi huruf mutu suatu mata kuliah.*

![Rekap Keseluruhan](ss/rekap%20nilai.png)
*Halaman laporan rekap keseluruhan.*

![Laporan PDF](ss/laporan%20pdf.png)
*Hasil ekspor dokumen ke format PDF.*

### 7. Pengaturan Sistem
![Setting Nilai Bobot](ss/setting%20nilai%20bobot.png)
*Halaman untuk mengatur persentase bobot tiap komponen nilai.*

![Backup Data](ss/backup%20data.png)
*Fitur backup dan pemulihan database sistem.*

![Monitor](ss/monitor.png)
*Monitoring penggunaan resource server.*

### 8. Pengaturan Akun
![Ubah Profile](ss/ubah%20profile.png)
*Halaman pengaturan data profil pengguna.*

![Ubah Password](ss/ubah%20password.png)
*Halaman pergantian kata sandi yang aman.*

---

## 🔄 Alur Kerja (Workflow) Sistem

1. **Pengaturan Master Data (Admin/Kaprodi):**
   - Menambahkan data **Kampus** dan **Kelas**.
   - Menambahkan data **Mata Kuliah** beserta detail bobot (Teori/Praktikum) dan jumlah pertemuannya.
   - Memasukkan data **Mahasiswa** ke dalam kelas.

2. **Manajemen Kelas (KRS/Pendaftaran):**
   - Mendaftarkan mahasiswa ke mata kuliah tertentu agar mereka masuk ke dalam daftar absensi dan penilaian.

3. **Operasional Harian (Dosen):**
   - **Absensi:** Dosen masuk ke menu Absensi, memilih pertemuan, dan mencatat status kehadiran mahasiswa. Keterangan spesifik (Sakit, Izin) dapat dicantumkan.
   - **Keaktifan:** Dosen masuk ke form Keaktifan, lalu mengklik sel mahasiswa untuk mencentang indikator keaktifan. Sistem mencegah skor melebihi batas maksimal (100).
   - **Tugas:** Dosen bebas menambahkan kolom-kolom tugas spesifik dan mengisi skornya. Sistem otomatis membagi rata-ratanya.

4. **Ujian & Penilaian Akhir (Akhir Semester):**
   - Dosen menginput nilai UTS dan UAS di form **Input Teori**.
   - Sistem secara otomatis menghitung Nilai Akademik dengan bobot baku: Kehadiran (25%), Tugas (20%), UTS (25%), UAS (30%).
   - Sistem menerapkan aturan nilai minimum berdasarkan kehadiran ($\ge 95\% \rightarrow 85$, $90-94.99\% \rightarrow 75$, $80-89.99\% \rightarrow 65$, $75-79.99\% \rightarrow 55$).
   - Nilai akhir diperoleh dari $\max(\text{Nilai Akademik}, \text{Nilai Minimum})$ untuk kehadiran $\ge 75\%$.
   - Sistem memvalidasi syarat kelulusan (Kehadiran $\ge 75\%$ DAN Nilai Akhir $\ge 55$). Mahasiswa dengan kehadiran $< 75\%$ otomatis berstatus **Tidak Lulus** tanpa penghapusan nilai asli.
   - Tombol **Kalkulasi Ulang** tersedia di halaman rekap nilai untuk menyinkronkan seluruh nilai mahasiswa kapan saja.

5. **Pelaporan (Laporan & Cetak):**
   - Nilai akhir terkonversi secara otomatis ke Huruf Mutu (A, B, C, D, E).
   - Pengelola mencetak Laporan Rekap Nilai Mata Kuliah atau mendistribusikan berkas dalam wujud Excel (XLSX) maupun PDF.

---

## ⚙️ Syarat Sistem & Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js & NPM (Opsional, untuk aset Vite)

### Langkah-langkah Instalasi

1. **Kloning Repositori**
   ```bash
   git clone https://github.com/username/nilai-mhs.git
   cd nilai-mhs
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Lingkungan (Environment)**
   Salin file konfigurasi bawaan dan sesuaikan kredensial database Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Buka file `.env` lalu sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` Anda.*

4. **Migrasi Database & Seeding**
   Siapkan struktur tabel dan data *dummy* awal dengan menjalankan perintah berikut:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di: `http://localhost:8000`

### Akun Login Default
- **Email:** `aslam11mardin@gmail.com`
- **Password:** `password`

*(Catatan: Sesuaikan kembali email ini jika Anda memodifikasi file DatabaseSeeder)*

---

## 🛠 Teknologi yang Digunakan
- **Backend:** Laravel 11+, PHP 8.2+
- **Frontend:** Blade Templating, Bootstrap 5, Vanilla JavaScript
- **Database:** MySQL
- **Library Ekspor:**
  - `barryvdh/laravel-dompdf` (PDF)
  - `maatwebsite/excel` (Excel)
