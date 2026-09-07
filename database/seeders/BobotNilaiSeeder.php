<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotNilaiSeeder extends Seeder {
    public function run(): void {
       DB::table('bobot_nilai')->updateOrInsert(
            ['id' => 1],
            [
                'kehadiran' => 25,
                'tugas'     => 20,
                'uts'       => 25,
                'uas'       => 30,
                'keaktifan' => 0,
                'akhlak'    => 0,
            ]
        );
    }
}
