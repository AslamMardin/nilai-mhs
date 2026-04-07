<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotNilaiSeeder extends Seeder {
    public function run(): void {
       DB::table('bobot_nilai')->updateOrInsert(
    ['id' => 1],
    [
        'akhlak' => 10,
        'keaktifan' => 20,
        'tugas' => 20,
        'uts' => 25,
        'uas' => 35,
    ]
);
    }
}
