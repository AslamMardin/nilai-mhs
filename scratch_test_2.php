<?php
$mk = App\Models\MataKuliah::first();
$mhs = $mk->mahasiswa()->first();

// Data Asli di DB (Sebelum dihitung ulang)
$nt = App\Models\NilaiTeori::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();
$na_lama = App\Models\NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();

$keaktifan_input = $nt->keaktifan;
$tugas = $nt->tugas;
$uts = $nt->uts;
$uas = $nt->uas;
$absensi = App\Models\Absensi::hitungPersen($mhs->id, $mk->id, $mk->total_pertemuan);
$na_teori_lama = $nt->nilai_akhir_teori;

echo "=== DATA MAHASISWA ===\n";
echo "Mahasiswa: " . $mhs->nama . "\n";
echo "Mata Kuliah: " . $mk->nama . "\n";
echo "Persentase Kehadiran: " . $absensi . "%\n\n";

echo "=== NILAI INPUT DOSEN ===\n";
echo "Keaktifan: " . $keaktifan_input . "\n";
echo "Tugas: " . $tugas . "\n";
echo "UTS: " . $uts . "\n";
echo "UAS: " . $uas . "\n\n";

echo "=== HASIL LAMA DI SISTEM (Sebelum Rumus Baru) ===\n";
echo "NA Teori Lama: " . $na_teori_lama . "\n";
echo "Nilai Akhir Keseluruhan (NA) Lama: " . ($na_lama ? $na_lama->nilai_akhir : 0) . "\n\n";

// --- TRIGGER PERHITUNGAN BARU (Berdasarkan Ide 3 & 2) ---
$nt->nilai_akhir_teori = $nt->hitung();
$nt->save();

$controller = new App\Http\Controllers\NilaiController();
$na_baru = $controller->hitungNilaiAkhir($mhs->id, $mk->id);

echo "=== HASIL SETELAH RUMUS BARU (Bukti Keberhasilan) ===\n";
echo "NA Teori (Setelah Keaktifan Dikatrol Ide 3): " . $nt->nilai_akhir_teori . "\n";
echo "Nilai Akhir Keseluruhan (Setelah Dapat Bonus NA Ide 2): " . $na_baru->nilai_akhir . "\n";
