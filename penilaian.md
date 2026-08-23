Lakukan perubahan pada **logika sistem penilaian mahasiswa** agar kehadiran menjadi **faktor utama dalam menentukan nilai akhir**, tetapi nilai akademik seperti keaktifan, tugas, UTS, dan UAS tetap diperhitungkan.

Sebelum melakukan perubahan, **telusuri seluruh source code dan alur perhitungan nilai yang sudah ada**. Jangan langsung mengubah satu fungsi saja. Periksa khususnya:

* `NilaiController.php`
* `NilaiTeori.php`
* `NilaiAkhir.php`
* Model yang berkaitan dengan nilai
* Model/database kehadiran
* Tabel `BobotNilai`
* Fungsi perhitungan `nilai_akhir`
* Fungsi `status_kelulusan`
* Fungsi konversi nilai angka ke huruf mutu
* Seluruh fungsi reward kehadiran
* Perhitungan mata kuliah teori
* Perhitungan mata kuliah praktikum
* Perhitungan mata kuliah teori + praktikum

## 1. RUMUS NILAI AKADEMIK

Gunakan komposisi berikut sebagai dasar perhitungan:

* Kehadiran = **50%**
* Keaktifan = **10%**
* Tugas = **15%**
* UTS = **10%**
* UAS = **15%**

Rumus:

**Nilai Akademik = (Kehadiran × 50%) + (Keaktifan × 10%) + (Tugas × 15%) + (UTS × 10%) + (UAS × 15%)**

Pastikan total bobot = **100%**.

Jika sistem saat ini menggunakan konfigurasi bobot dari tabel `BobotNilai`, sesuaikan mekanismenya agar aturan baru tetap dapat diterapkan secara konsisten dan tidak terjadi konflik antara konfigurasi lama dengan logika baru.

## 2. KONVERSI NILAI KEHADIRAN

Persentase kehadiran digunakan sebagai nilai komponen kehadiran dengan ketentuan:

* 100% → 100
* 95–99,99% → 95
* 90–94,99% → 90
* 85–89,99% → 85
* 80–84,99% → 80
* 75–79,99% → 75
* <75% → tidak memenuhi syarat kelulusan

## 3. KEHADIRAN SEBAGAI FAKTOR UTAMA

Setelah `Nilai Akademik` dihitung, terapkan **batas minimal nilai berdasarkan persentase kehadiran**.

Ketentuannya:

| Persentase Kehadiran | Nilai Minimal |
| -------------------- | ------------: |
| ≥ 95%                |            85 |
| 90–94,99%            |            75 |
| 80–89,99%            |            65 |
| 75–79,99%            |            55 |
| <75%                 |   Tidak Lulus |

Gunakan logika:

**Nilai Akhir = nilai terbesar antara Nilai Akademik dan Nilai Minimal Berdasarkan Kehadiran**

Contoh:

* Kehadiran 100%, Nilai Akademik 60 → **Nilai Akhir 85 (A)**
* Kehadiran 97%, Nilai Akademik 80 → **Nilai Akhir 85 (A)**
* Kehadiran 92%, Nilai Akademik 60 → **Nilai Akhir 75 (B)**
* Kehadiran 90%, Nilai Akademik 80 → **Nilai Akhir 80 (B)**
* Kehadiran 85%, Nilai Akademik 60 → **Nilai Akhir 65 (C)**
* Kehadiran 85%, Nilai Akademik 90 → **Nilai Akhir 90 (A)**
* Kehadiran 77%, Nilai Akademik 50 → **Nilai Akhir 55 (D)**
* Kehadiran 74%, Nilai Akademik 95 → **Tidak Lulus**

## 4. SYARAT KELULUSAN

Mahasiswa dinyatakan **LULUS** hanya jika:

1. Persentase kehadiran **≥75%**
2. Nilai Akhir **≥55**

Jika kehadiran <75%, mahasiswa harus tetap berstatus **Tidak Lulus**, meskipun nilai UTS, UAS, tugas, atau nilai akademiknya tinggi.

Sistem juga harus menyimpan alasan ketidaklulusan, misalnya:

* `Kehadiran 74% (minimal 75%)`
* `Nilai akhir 50 (minimal 55)`
* atau jika keduanya tidak terpenuhi, tampilkan kedua alasan tersebut.

## 5. KONVERSI HURUF MUTU

Pertahankan aturan:

* **A** = ≥85
* **B** = 75–84,99
* **C** = 65–74,99
* **D** = 55–64,99
* **E** = <55

Huruf mutu harus dihitung **setelah Nilai Akhir final diperoleh**.

## 6. HAPUS REWARD KEHADIRAN LAMA

Nonaktifkan atau hapus logika reward kehadiran lama:

* 100% → +5
* ≥90% → +3
* ≥80% → +1

Jangan lagi menambahkan bonus tersebut secara langsung ke `nilai_akhir`, karena kehadiran sekarang sudah memiliki bobot **50%** dan memiliki sistem batas minimal nilai.

Pastikan tidak ada fungsi lama yang masih menambahkan bonus tersebut setelah perhitungan nilai baru.

## 7. JENIS MATA KULIAH

Pertahankan mekanisme jenis mata kuliah yang sudah ada:

### Mata Kuliah Teori

Tetap gunakan perhitungan nilai teori, kemudian terapkan aturan kehadiran terhadap hasil akhirnya.

### Mata Kuliah Praktikum

Tetap gunakan perhitungan nilai praktikum, kemudian terapkan aturan kehadiran terhadap hasil akhirnya.

### Mata Kuliah Teori + Praktikum

Tetap gunakan mekanisme kombinasi teori dan praktikum yang sudah ada, kemudian terapkan aturan kehadiran terhadap nilai akademik final.

Jangan menghilangkan fungsi perhitungan teori/praktikum yang sudah ada kecuali memang bertentangan langsung dengan aturan baru.

## 8. PENTING: JANGAN BIARKAN LOGIKA LAMA MENIMPA NILAI BARU

Telusuri seluruh proses setelah `nilai_akhir` dihitung.

Pastikan tidak ada proses lain yang:

* menghitung ulang `nilai_akhir`
* menambahkan reward lama
* mengurangi nilai berdasarkan kehadiran
* mengganti huruf mutu
* mengganti `status_kelulusan`
* menggunakan batas kelulusan lama
* menyimpan nilai berbeda dari hasil perhitungan baru.

Jika terdapat beberapa fungsi yang menghitung nilai akhir, satukan atau pastikan semuanya menggunakan aturan baru yang konsisten.

## 9. PENGUJIAN WAJIB

Setelah implementasi, lakukan pengujian minimal berikut:

| Kehadiran | Nilai Akademik | Nilai Akhir | Huruf | Status      |
| --------: | -------------: | ----------: | :---: | :---------- |
|      100% |             60 |          85 |   A   | Lulus       |
|       97% |             80 |          85 |   A   | Lulus       |
|       92% |             60 |          75 |   B   | Lulus       |
|       90% |             80 |          80 |   B   | Lulus       |
|       85% |             60 |          65 |   C   | Lulus       |
|       85% |             90 |          90 |   A   | Lulus       |
|       77% |             50 |          55 |   D   | Lulus       |
|       74% |             95 |           — |   E   | Tidak Lulus |

Tambahkan pengujian untuk nilai batas seperti:

* 94,99%
* 95%
* 89,99%
* 90%
* 79,99%
* 80%
* 74,99%
* 75%

Pastikan tidak terjadi kesalahan pembulatan atau kesalahan kondisi `>=` dan `<`.

## 10. TUJUAN AKHIR

Tujuan utama perubahan ini adalah:

**Semakin tinggi kehadiran mahasiswa, semakin tinggi penghargaan terhadap nilai akhirnya.**

Mahasiswa dengan kehadiran **≥95%** mendapatkan perlakuan khusus berupa **nilai akhir minimal 85 (A)**.

Namun nilai akademik tetap dihitung sehingga mahasiswa dengan kemampuan akademik lebih tinggi tetap mendapatkan nilai sesuai hasil akademiknya.

Dengan demikian, sistem tidak menggunakan prinsip:

> "Hadir sedikit langsung gagal" saja,

tetapi menggunakan prinsip:

> **Kehadiran adalah faktor utama dan bentuk penghargaan terhadap kedisiplinan, sedangkan nilai akademik tetap menjadi bagian dari penilaian.**

Jangan mengubah tampilan/UI yang tidak berkaitan dengan sistem penilaian. Fokus pada **backend, algoritma perhitungan, penyimpanan nilai, status kelulusan, dan konsistensi seluruh alur penilaian**.

Setelah selesai melakukan perubahan, tampilkan ringkasan file/fungsi yang diubah dan jelaskan secara singkat bagaimana alur perhitungan nilai yang baru bekerja.
