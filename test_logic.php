<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tests = [
    // Kehadiran, Nilai Akademik, Expected Final, Expected Huruf, Expected Status
    [100, 60, 85, 'A', 'lulus'],
    [97, 80, 85, 'A', 'lulus'],
    [92, 60, 75, 'B', 'lulus'],
    [90, 80, 80, 'B', 'lulus'],
    [85, 60, 65, 'C', 'lulus'],
    [85, 90, 90, 'A', 'lulus'],
    [77, 50, 55, 'D', 'lulus'],
    [74, 95, 0, 'E', 'tidak_lulus'], 
];

foreach($tests as $t) {
    $persen = $t[0];
    $nilaiAkademik = $t[1];
    
    // Logika dari NilaiController
    $batasMinimal = match(true) {
        $persen >= 95 => 85,
        $persen >= 90 => 75,
        $persen >= 80 => 65,
        $persen >= 75 => 55,
        default       => 0,
    };

    $nilaiAkhir = max($nilaiAkademik, $batasMinimal);
    $lolos = $persen >= 75.0;

    if ($nilaiAkhir > 100) $nilaiAkhir = 100;

    $keteranganGagal = null;
    if (!$lolos) {
        $status = 'tidak_lulus';
        $nilaiAkhir = 0;
    } elseif ($nilaiAkhir < 55) {
        $status = 'tidak_lulus';
    } else {
        $status = 'lulus';
    }

    $huruf = App\Models\NilaiAkhir::toHuruf($nilaiAkhir);

    $pass = ($nilaiAkhir == $t[2] && $huruf === $t[3] && $status === $t[4]) ? 'PASS' : 'FAIL';
    if ($pass === 'FAIL') {
        echo "FAIL: H=$persen, NA=$nilaiAkademik. Expected $t[2] $t[3] $t[4]. Got $nilaiAkhir $huruf $status\n";
    } else {
        echo "PASS: H=$persen, NA=$nilaiAkademik -> $nilaiAkhir $huruf $status\n";
    }
}
