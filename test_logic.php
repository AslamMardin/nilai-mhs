<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\PenilaianService;

echo "=== 1. PENGUJIAN TABEL WAJIB (Bagian 15) ===\n";
$testsWajib = [
    // Hadir, Akademik, Expected NA, Expected Huruf, Expected Status
    [100, 60, 85, 'A', 'lulus'],
    [97,  80, 85, 'A', 'lulus'],
    [92,  60, 75, 'B', 'lulus'],
    [90,  80, 80, 'B', 'lulus'],
    [85,  60, 65, 'C', 'lulus'],
    [85,  90, 90, 'A', 'lulus'],
    [77,  50, 55, 'D', 'lulus'],
    [74,  95, 95, 'A', 'tidak_lulus'],
];

$allPassed = true;
foreach ($testsWajib as $t) {
    $hadir = (float) $t[0];
    $akademik = (float) $t[1];
    $expNA = (float) $t[2];
    $expHuruf = $t[3];
    $expStatus = $t[4];

    $na = PenilaianService::hitungNilaiAkhir($akademik, $hadir);
    $huruf = PenilaianService::konversiHurufMutu($na);
    $status = PenilaianService::tentukanStatusKelulusan($hadir, $na);

    $passed = ($na == $expNA && $huruf === $expHuruf && $status === $expStatus);
    if ($passed) {
        echo "✅ PASS: Hadir {$hadir}%, Akademik {$akademik} -> NA {$na}, Huruf {$huruf}, Status {$status}\n";
    } else {
        echo "❌ FAIL: Hadir {$hadir}%, Akademik {$akademik} -> Got (NA {$na}, Huruf {$huruf}, Status {$status}) Expected (NA {$expNA}, Huruf {$expHuruf}, Status {$expStatus})\n";
        $allPassed = false;
    }
}

echo "\n=== 2. PENGUJIAN BATAS KEHADIRAN (Bagian 16) ===\n";
$testsBatas = [
    [94.99, 75],
    [95.00, 85],
    [89.99, 65],
    [90.00, 75],
    [79.99, 55],
    [80.00, 65],
    [74.99, null],
    [75.00, 55],
];
foreach ($testsBatas as [$h, $expMin]) {
    $min = PenilaianService::hitungMinimumKehadiran($h);
    $pass = ($min === (float) $expMin || ($expMin === null && $min === null));
    $minText = $min === null ? 'Tidak Lulus' : "minimum {$min}";
    $expText = $expMin === null ? 'Tidak Lulus' : "minimum {$expMin}";
    if ($pass) {
        echo "✅ PASS: {$h}% -> {$minText}\n";
    } else {
        echo "❌ FAIL: {$h}% -> Got {$minText}, Expected {$expText}\n";
        $allPassed = false;
    }
}

echo "\n=== 3. PENGUJIAN RUMUS BOBOT (Bagian 17) ===\n";
$h = 80; $tg = 70; $uts = 60; $uas = 50;
$akademik = PenilaianService::hitungNilaiTeori($h, $tg, $uts, $uas);
$na = PenilaianService::hitungNilaiAkhir($akademik, $h);
$huruf = PenilaianService::konversiHurufMutu($na);
$status = PenilaianService::tentukanStatusKelulusan($h, $na);
if ($akademik == 64 && $na == 65 && $huruf === 'C' && $status === 'lulus') {
    echo "✅ PASS: Akademik={$akademik}, Min=65, NA={$na}, Huruf={$huruf}, Status={$status}\n";
} else {
    echo "❌ FAIL: Rumus bobot mismatch: Akademik={$akademik}, NA={$na}, Huruf={$huruf}, Status={$status}\n";
    $allPassed = false;
}

echo "\n=== 4. PENGUJIAN TANPA REWARD (Bagian 18) ===\n";
$na1 = PenilaianService::hitungNilaiAkhir(90, 100);
$na2 = PenilaianService::hitungNilaiAkhir(80, 100);
echo ($na1 == 90 ? "✅ PASS" : "❌ FAIL") . ": Hadir 100%, Akademik 90 -> NA {$na1} (bukan 95)\n";
echo ($na2 == 85 ? "✅ PASS" : "❌ FAIL") . ": Hadir 100%, Akademik 80 -> NA {$na2} (bukan 85 karena bonus)\n";
if ($na1 != 90 || $na2 != 85) $allPassed = false;

echo "\n=== 5. PENGUJIAN ALASAN KETIDAKLULUSAN (Bagian 6) ===\n";
$k1 = PenilaianService::tentukanKeteranganGagal(59, 95);
$k2 = PenilaianService::tentukanKeteranganGagal(80, 50);
$k3 = PenilaianService::tentukanKeteranganGagal(59, 50);
$k4 = PenilaianService::tentukanKeteranganGagal(80, 65);
echo ($k1 === 'Kehadiran 59% (minimal 60%)' ? "✅ PASS" : "❌ FAIL") . ": {$k1}\n";
echo ($k2 === 'Nilai akhir 50 (minimal 55)' ? "✅ PASS" : "❌ FAIL") . ": {$k2}\n";
echo ($k3 === 'Kehadiran 59% (minimal 60%); Nilai akhir 50 (minimal 55)' ? "✅ PASS" : "❌ FAIL") . ": {$k3}\n";
echo ($k4 === null ? "✅ PASS" : "❌ FAIL") . ": Lulus -> null\n";
if ($k1 !== 'Kehadiran 59% (minimal 60%)' || $k2 !== 'Nilai akhir 50 (minimal 55)' || $k3 !== 'Kehadiran 59% (minimal 60%); Nilai akhir 50 (minimal 55)' || $k4 !== null) {
    $allPassed = false;
}

echo "\n============================================\n";
echo $allPassed ? "🎉 SEMUA TEST CASE SESUAI SPESIFIKASI PENILAIAN.MD!\n" : "⚠️ TERDAPAT TEST YANG GAGAL!\n";
