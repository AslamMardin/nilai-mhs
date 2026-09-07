

* Jangan lagi menilai menggunakan **Nilai Akhir: Teori 50% + Praktikum 50%**.
* Hapus/nonaktifkan pemisahan bobot **Teori 50% dan Praktikum 50%**.
* Gunakan **bobot penilaian yang sudah ditetapkan dengan total 100%**, yaitu:

  * **Kehadiran = 25%**
  * **Tugas Terstruktur & Mandiri = 20%**
  * **UTS = 25%**
  * **UAS = 30%**
* Nilai akhir dihitung langsung berdasarkan keempat komponen tersebut.
* **Praktikum tidak menjadi komponen/bobot terpisah**, tetapi dapat dinilai melalui **Tugas, UTS, dan/atau UAS** sesuai bentuk penilaian mata kuliah.
* **Hapus inputan nilai praktikum dari sistem**, termasuk form, field, proses penyimpanan, perhitungan, dan tampilan yang secara khusus digunakan untuk memasukkan **Nilai Praktikum** sebagai nilai terpisah.
* Jangan menghapus aktivitas/tugas praktikum yang masih diperlukan. Jika ada tugas atau ujian praktik, nilainya tetap dimasukkan melalui komponen **Tugas, UTS, atau UAS**.
* Hapus/nonaktifkan seluruh logic yang menghitung **Nilai Praktikum 50%** atau menggabungkan **Nilai Teori 50% + Nilai Praktikum 50%**.
* Pastikan tidak ada **Nilai Praktikum** yang masih ikut dihitung secara tersembunyi di backend, service, controller, model, database query, rekap, atau laporan.
* Jangan menambahkan bobot praktikum di luar total 100%.
* Setelah revisi, seluruh perhitungan nilai harus menggunakan:
  **Kehadiran 25% + Tugas 20% + UTS 25% + UAS 30% = 100%.**
