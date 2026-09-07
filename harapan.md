Periksa dan perbaiki seluruh logika perhitungan nilai mahasiswa pada sistem. Jangan mengubah desain/tampilan yang sudah ada kecuali diperlukan untuk memperbaiki informasi yang salah.

Gunakan aturan penilaian 100% berikut:

Hadir = 25%
Tugas = 20%
UTS = 25%
UAS = 30%
Total = 100%

1. Perbaiki perhitungan NA Murni

Rumus yang WAJIB digunakan:

NA Murni = (Hadir × 25%) + (Tugas × 20%) + (UTS × 25%) + (UAS × 30%)

Jangan gunakan nilai lain yang tersembunyi, nilai praktikum, nilai lama, atau nilai alternatif dalam perhitungan.

2. Perbaiki masalah nilai Tugas

Saat ini terdapat ketidaksesuaian antara nilai Tugas yang ditampilkan dengan nilai yang digunakan dalam perhitungan.

Contoh:

ABD. WAHAB: Tugas ditampilkan 99,14, tetapi NA Murni dihitung seolah-olah Tugas = 93,33.
ADILA SYAHRA: Tugas ditampilkan 98,08, tetapi NA Murni dihitung seolah-olah Tugas = 91,67.

Ini harus diperbaiki.

Nilai yang ditampilkan sebagai nilai Tugas harus sama persis dengan nilai Tugas yang digunakan dalam perhitungan NA Murni.

Jangan mengambil nilai Tugas dari field/database/perhitungan lain.

3. Hapus penggunaan nilai Praktikum

Jangan lagi memasukkan nilai praktikum ke dalam perhitungan nilai akhir.

Jika masih ada field atau logika nilai praktikum yang digunakan dalam perhitungan, lepaskan dari perhitungan.

4. Keaktifan sebagai BONUS

Keaktifan bukan komponen bobot 100%.

Keaktifan hanya merupakan BONUS maksimal 10%.

Rumus:

Bonus Keaktifan = Keaktifan × 10%

Contoh:

Keaktifan 58,13 → Bonus = 5,81
Keaktifan 64,06 → Bonus = 6,41

Bonus harus benar-benar diterapkan ke Nilai Finish, bukan hanya ditampilkan.

Rumus:

Nilai Finish = NA Murni + Bonus Keaktifan

Jika hasil lebih dari 100, batasi menjadi:

Nilai Finish = 100

5. Perbaiki tampilan kolom

Pastikan hubungan datanya jelas:

Hadir (25%) | Keaktifan (Bonus 10%) | Tugas (20%) | UTS (25%) | UAS (30%) | NA Murni (100%) | Bonus | Nilai Finish | NA | Mutu | Status

Nilai Tugas yang tampil harus merupakan nilai yang benar-benar digunakan dalam rumus.

6. Contoh validasi

Untuk ABD. WAHAB:

Hadir = 87,5
Tugas = 99,14
UTS = 63
UAS = 80
Keaktifan = 58,13
Bonus Keaktifan = 5,81

Maka:

NA Murni = (87,5×0,25) + (99,14×0,20) + (63×0,25) + (80×0,30)

NA Murni = 81,45

Kemudian:

Nilai Finish = 81,45 + 5,81 = 87,26

Jadi sistem harus menghasilkan:

NA Murni = 81,45
Bonus = +5,81
Nilai Finish = 87,26

7. Jangan hanya memperbaiki tampilan

Periksa juga backend, controller, model, service, query/database, JavaScript, dan fungsi kalkulasi yang berkaitan dengan nilai.

Cari sumber mengapa nilai dalam tanda kurung seperti (93,33) dan (91,67) masih digunakan dalam perhitungan meskipun nilai Tugas yang ditampilkan adalah 99,14 dan 98,08.

Perbaiki sumber masalahnya, bukan hanya mengganti angka pada tampilan.

Setelah selesai, lakukan pengecekan terhadap seluruh mahasiswa untuk memastikan:

Nilai Tugas yang tampil = nilai Tugas yang dihitung.
NA Murni menggunakan bobot tepat 25% + 20% + 25% + 30%.
Praktikum tidak ikut dihitung.
Bonus Keaktifan benar-benar masuk ke Nilai Finish.
Nilai Finish tidak boleh lebih dari 100.
NA dan Mutu menggunakan Nilai Finish.
Tidak ada perhitungan nilai lama/tersembunyi yang masih memengaruhi hasil.

Jangan mengubah bobot penilaian di atas. Fokus hanya memperbaiki implementasi agar sesuai dengan aturan tersebut.