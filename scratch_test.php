<?php
$mk = App\Models\MataKuliah::first();
if(!$mk) { echo "Tidak ada Mata Kuliah\n"; exit; }
$mhs = $mk->mahasiswa()->first();
if(!$mhs) { echo "Tidak ada Mahasiswa\n"; exit; }
$nt = App\Models\NilaiTeori::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();
$na = App\Models\NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();
$absensi = App\Models\Absensi::hitungPersen($mhs->id, $mk->id, $mk->total_pertemuan);

echo "Mahasiswa: " . $mhs->nama . "\n";
echo "Mata Kuliah: " . $mk->nama . "\n";
echo "Persentase Kehadiran: " . $absensi . "%\n";

if($nt) {
    echo "Nilai Keaktifan Input (DB): " . $nt->keaktifan . "\n";
    echo "Nilai Tugas: " . $nt->tugas . "\n";
    echo "Nilai UTS: " . $nt->uts . "\n";
    echo "Nilai UAS: " . $nt->uas . "\n";
    echo "NA Teori (Setelah Rumus): " . $nt->nilai_akhir_teori . "\n";
} else {
    echo "Belum ada Nilai Teori\n";
}

if($na) {
    echo "Nilai Akhir Keseluruhan (Termasuk Bonus): " . $na->nilai_akhir . "\n";
} else {
    echo "Belum ada Nilai Akhir\n";
}
