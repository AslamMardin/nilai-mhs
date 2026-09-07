# PROMPT REVISI SISTEM PENILAIAN MAHASISWA

Lakukan revisi menyeluruh pada **logika sistem penilaian mahasiswa**. Fokus pada backend, algoritma perhitungan, database, penyimpanan nilai, status kelulusan, dan konsistensi seluruh alur penilaian. **Jangan mengubah UI/tampilan yang tidak berkaitan dengan sistem penilaian.**

## 1. Audit Source Code

Sebelum mengubah kode, telusuri seluruh source code dan alur penilaian yang berkaitan dengan:

* `NilaiController.php`
* `NilaiTeori.php`
* `NilaiAkhir.php`
* seluruh Model nilai
* Model/database kehadiran
* `BobotNilai`
* fungsi `nilai_akhir`
* fungsi `status_kelulusan`
* fungsi konversi nilai angka ke huruf mutu
* seluruh fungsi reward/bonus kehadiran
* perhitungan mata kuliah teori
* perhitungan mata kuliah praktikum
* perhitungan mata kuliah teori + praktikum
* proses penyimpanan dan update nilai
* proses kalkulasi ulang nilai
* service/helper/observer/event/listener yang berkaitan dengan nilai

Cari seluruh penggunaan:

```text
nilai_akhir
status_kelulusan
huruf_mutu
bobot
kehadiran
reward
bonus
```

Pastikan tidak ada lebih dari satu logika yang menghitung nilai akhir dengan aturan berbeda.

---

# 2. Bobot Penilaian Final

Gunakan bobot berikut:

| Komponen                      |    Bobot |
| ----------------------------- | -------: |
| Kehadiran                     |  **25%** |
| Tugas Terstruktur dan Mandiri |  **20%** |
| Tugas Mandiri (UTS)           |  **25%** |
| Tugas Mandiri (UAS)           |  **30%** |
| **Total**                     | **100%** |

Rumus:

```text
Nilai Akademik =
(Kehadiran × 0.25)
+ (Tugas × 0.20)
+ (UTS × 0.25)
+ (UAS × 0.30)
```

Jangan membuat komponen `Keaktifan` sebagai bobot terpisah.

Jika keaktifan sudah termasuk dalam Tugas Terstruktur dan Mandiri, pertahankan mekanisme tersebut.

---

# 3. Nilai Kehadiran

Gunakan **persentase kehadiran aktual** sebagai nilai komponen kehadiran.

Contoh:

```text
100% → 100
97%  → 97
92%  → 92
87%  → 87
82%  → 82
77%  → 77
75%  → 75
```

Jangan membulatkan:

```text
97% → 95
92% → 90
87% → 85
```

Nilai aktual digunakan dalam bobot 25%.

Jika memungkinkan, persentase dihitung dari:

```text
Persentase Kehadiran =
(Jumlah Hadir / Total Pertemuan) × 100
```

Jangan melakukan pembulatan yang dapat menyebabkan mahasiswa berpindah kategori batas kehadiran.

---

# 4. Minimum Nilai Berdasarkan Kehadiran

Setelah `Nilai Akademik` dihitung, tentukan nilai minimum berdasarkan persentase kehadiran:

|     Kehadiran |   Nilai Minimum |
| ------------: | --------------: |
|      **≥95%** |          **85** |
| **90–94,99%** |          **75** |
| **80–89,99%** |          **65** |
| **75–79,99%** |          **55** |
|      **<75%** | **Tidak Lulus** |

Untuk mahasiswa dengan kehadiran ≥75%:

```text
Nilai Akhir =
MAX(Nilai Akademik, Nilai Minimum Berdasarkan Kehadiran)
```

Contoh:

```text
Kehadiran = 92%
Nilai Akademik = 60
Minimum = 75

Nilai Akhir = 75
```

Jika:

```text
Kehadiran = 85%
Nilai Akademik = 90
Minimum = 65
```

maka:

```text
Nilai Akhir = 90
```

Jadi nilai akademik yang lebih tinggi **tidak boleh diturunkan** oleh aturan minimum kehadiran.

---

# 5. Syarat Kelulusan

Mahasiswa dinyatakan **LULUS** hanya jika:

```text
Kehadiran >= 75%
DAN
Nilai Akhir >= 55
```

Jika kehadiran <75%, mahasiswa **WAJIB Tidak Lulus**, meskipun nilai akademiknya tinggi.

Contoh:

```text
Kehadiran = 74%
Nilai Akademik = 95
Nilai Akhir = 95

Status = Tidak Lulus
```

---

# 6. Alasan Ketidaklulusan

Sistem harus menyimpan/menentukan alasan ketidaklulusan.

Jika kehadiran <75%:

```text
Kehadiran 74% (minimal 75%)
```

Jika nilai akhir <55:

```text
Nilai akhir 50 (minimal 55)
```

Jika keduanya tidak terpenuhi:

```text
Kehadiran 70% (minimal 75%); Nilai akhir 50 (minimal 55)
```

Jika lulus, tidak ada alasan ketidaklulusan.

---

# 7. Konversi Huruf Mutu

Konversi huruf dilakukan **setelah Nilai Akhir final diperoleh**.

|  Nilai Akhir | Huruf |
| -----------: | :---: |
|      **≥85** |   A   |
| **75–84,99** |   B   |
| **65–74,99** |   C   |
| **55–64,99** |   D   |
|      **<55** |   E   |

Urutan proses wajib:

```text
Nilai Akademik
      ↓
Minimum Berdasarkan Kehadiran
      ↓
Nilai Akhir Final
      ↓
Huruf Mutu
      ↓
Status Kelulusan
```

---

# 8. Reward Kehadiran

Hapus/nonaktifkan seluruh reward kehadiran berupa:

```text
100% → +5
≥90% → +3
≥80% → +1
```

Jangan lagi melakukan:

```text
Nilai Akhir = Nilai Akademik + Bonus Kehadiran
```

Kehadiran sudah diperhitungkan melalui:

* bobot 25%;
* minimum nilai berdasarkan kehadiran;
* syarat kehadiran minimal 75%.

Pastikan tidak ada fungsi lama yang masih menambahkan bonus setelah `nilai_akhir` dihitung.

---

# 9. Mata Kuliah Teori

Pertahankan mekanisme perhitungan teori yang sudah ada.

Setelah nilai akademik teori diperoleh, terapkan:

```text
Nilai Akademik
      ↓
Aturan Minimum Kehadiran
      ↓
Nilai Akhir
      ↓
Huruf Mutu
      ↓
Status Kelulusan
```

Jangan menghapus fungsi teori kecuali memang bertentangan dengan aturan baru.

---

# 10. Mata Kuliah Praktikum

Pertahankan mekanisme perhitungan praktikum yang sudah ada.

Setelah nilai akademik praktikum diperoleh, terapkan aturan kehadiran dan kelulusan yang sama.

Jangan menghapus mekanisme praktikum yang sudah ada.

---

# 11. Mata Kuliah Teori + Praktikum

Pertahankan mekanisme kombinasi teori dan praktikum yang sudah ada.

Alurnya:

```text
Nilai Teori
     +
Nilai Praktikum
     ↓
Nilai Akademik Final
     ↓
Aturan Kehadiran
     ↓
Nilai Akhir
     ↓
Huruf Mutu
     ↓
Status Kelulusan
```

Pastikan aturan kehadiran tidak diterapkan dua kali.

---

# 12. BobotNilai

Jika sistem menggunakan tabel `BobotNilai`, pastikan konfigurasi yang digunakan adalah:

```text
Kehadiran = 25
Tugas     = 20
UTS       = 25
UAS       = 30
```

Pastikan seluruh Controller, Model, Service, Helper, dan query menggunakan bobot yang sama.

Jangan ada konfigurasi bobot yang berbeda di tempat lain.

---

# 13. Konsistensi Nilai Akhir

Pastikan tidak ada proses yang setelah `nilai_akhir` dihitung kemudian:

* menghitung ulang nilai akhir;
* menambahkan bonus;
* mengurangi nilai;
* mengganti huruf mutu;
* mengganti status kelulusan;
* menggunakan batas kelulusan berbeda;
* menggunakan bobot berbeda;
* menyimpan nilai berbeda.

Jika terdapat beberapa fungsi yang menghitung `nilai_akhir`, satukan atau arahkan seluruhnya ke satu logika perhitungan utama.

---

# 14. Pembulatan

Gunakan nilai aktual untuk perhitungan.

Khusus persentase kehadiran, jangan membulatkan sebelum menentukan kategori minimum.

Contoh:

```text
89,99% → minimum 65
90%    → minimum 75

79,99% → minimum 55
80%    → minimum 65

74,99% → Tidak Lulus
75%    → minimum 55
```

Pastikan operator kondisi menggunakan batas inklusif dengan benar:

```text
>=95
>=90
>=80
>=75
<75
```

---

# 15. Pengujian Wajib

Lakukan pengujian berikut:

| Kehadiran | Nilai Akademik | Minimum | Nilai Akhir | Huruf |    Status   |
| --------: | -------------: | ------: | ----------: | :---: | :---------: |
|      100% |             60 |      85 |      **85** |   A   |    Lulus    |
|       97% |             80 |      85 |      **85** |   A   |    Lulus    |
|       92% |             60 |      75 |      **75** |   B   |    Lulus    |
|       90% |             80 |      75 |      **80** |   B   |    Lulus    |
|       85% |             60 |      65 |      **65** |   C   |    Lulus    |
|       85% |             90 |      65 |      **90** |   A   |    Lulus    |
|       77% |             50 |      55 |      **55** |   D   |    Lulus    |
|       74% |             95 |       - |      **95** |   A*  | Tidak Lulus |

`*` Huruf mutu tetap berdasarkan nilai angka, tetapi status tetap Tidak Lulus karena kehadiran <75%.

---

# 16. Pengujian Batas Kehadiran

Wajib menguji:

```text
94,99% → minimum 75
95%    → minimum 85

89,99% → minimum 65
90%    → minimum 75

79,99% → minimum 55
80%    → minimum 65

74,99% → Tidak Lulus
75%    → minimum 55
```

Pastikan tidak ada kesalahan akibat pembulatan maupun operator kondisi.

---

# 17. Pengujian Rumus Bobot

Gunakan contoh:

```text
Kehadiran = 80
Tugas     = 70
UTS       = 60
UAS       = 50
```

Perhitungan:

```text
(80 × 0.25)
+ (70 × 0.20)
+ (60 × 0.25)
+ (50 × 0.30)

= 64
```

Karena kehadiran 80%, minimum nilai = 65.

Maka:

```text
Nilai Akhir = MAX(64, 65)
            = 65

Huruf = C
Status = Lulus
```

---

# 18. Pengujian Tanpa Reward

Pastikan mahasiswa dengan:

```text
Kehadiran = 100%
Nilai Akademik = 90
```

mendapat:

```text
Nilai Akhir = 90
```

bukan:

```text
90 + 5 = 95
```

Sedangkan:

```text
Kehadiran = 100%
Nilai Akademik = 80
```

mendapat:

```text
Nilai Akhir = 85
```

karena minimum berdasarkan kehadiran, **bukan karena bonus +5**.

---

# 19. Batas Nilai

Pastikan:

```text
0 <= Nilai Akhir <= 100
```

Nilai akhir tidak boleh melebihi 100 akibat bonus atau proses kalkulasi lain.

---

# 20. Hasil Setelah Implementasi

Setelah selesai melakukan perubahan, tampilkan:

### File yang diperiksa

Daftar seluruh file yang diperiksa.

### File yang diubah

Hanya file yang benar-benar diubah.

### Fungsi yang diubah

Tampilkan nama class/method/function yang benar-benar diubah.

### Fungsi reward yang dinonaktifkan

Tampilkan seluruh fungsi reward kehadiran lama yang sudah tidak digunakan.

### Alur perhitungan final

```text
Kehadiran Aktual
      ↓
Nilai Akademik
      ↓
Minimum Berdasarkan Kehadiran
      ↓
Nilai Akhir Final
      ↓
Huruf Mutu
      ↓
Status Kelulusan
      ↓
Simpan Nilai
```

### Hasil pengujian

Tampilkan seluruh hasil test case, termasuk batas:

* 94,99%
* 95%
* 89,99%
* 90%
* 79,99%
* 80%
* 74,99%
* 75%

Pastikan seluruh hasil sesuai dengan aturan yang ditetapkan di atas.

---

# ATURAN FINAL

```text
KEHADIRAN = 25%
TUGAS     = 20%
UTS       = 25%
UAS       = 30%

TOTAL = 100%

NILAI AKADEMIK =
(Kehadiran × 0.25)
+ (Tugas × 0.20)
+ (UTS × 0.25)
+ (UAS × 0.30)

MINIMUM KEHADIRAN:

>=95%      → 85
90–94,99%  → 75
80–89,99%  → 65
75–79,99%  → 55
<75%       → Tidak Lulus

Jika kehadiran >=75%:

Nilai Akhir =
MAX(Nilai Akademik, Minimum Kehadiran)

Jika kehadiran <75%:

Status = TIDAK LULUS

HURUF:

>=85      → A
75–84,99  → B
65–74,99  → C
55–64,99  → D
<55       → E

REWARD KEHADIRAN:
+5 / +3 / +1 → TIDAK DIGUNAKAN
```

**Jangan mengubah UI yang tidak berkaitan dengan penilaian. Fokus pada backend, algoritma, database, penyimpanan nilai, status kelulusan, dan konsistensi seluruh alur penilaian.**