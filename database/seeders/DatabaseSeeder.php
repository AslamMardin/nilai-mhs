<?php namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Carbon\Carbon;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // ── Kampus ──
        $itbm  = DB::table('kampus')->insertGetId(['nama'=>'Institut Teknologi dan Bisnis Muhammadiyah Majene','kode'=>'ITBM','alamat'=>'Jl. Poros Majene, Sulawesi Barat','telepon'=>'0422-12345','created_at'=>now(),'updated_at'=>now()]);
        $stain = DB::table('kampus')->insertGetId(['nama'=>'Sekolah Tinggi Agama Islam Negeri Majene','kode'=>'STAIN','alamat'=>'Jl. BPD, Majene, Sulawesi Barat','telepon'=>'0422-67890','created_at'=>now(),'updated_at'=>now()]);

        // ── Users ──
        DB::table('users')->insert([
            ['name'=>'Super Admin','email'=>'admin@penilaian.ac.id','password'=>Hash::make('password'),'role'=>'superadmin','kampus_id'=>$itbm,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Admin ITBM','email'=>'admin@itbm.ac.id','password'=>Hash::make('password'),'role'=>'admin','kampus_id'=>$itbm,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Admin STAIN','email'=>'admin@stain.ac.id','password'=>Hash::make('password'),'role'=>'admin','kampus_id'=>$stain,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Kelas ITBM (3) ──
        $ti_a  = DB::table('kelas')->insertGetId(['kampus_id'=>$itbm,'nama'=>'Teknik Informatika A','kode'=>'TI-A','semester'=>'ganjil','tahun_ajaran'=>2024,'wali_kelas'=>'Dr. Ahmad Fauzi, M.T','created_at'=>now(),'updated_at'=>now()]);
        $ti_b  = DB::table('kelas')->insertGetId(['kampus_id'=>$itbm,'nama'=>'Teknik Informatika B','kode'=>'TI-B','semester'=>'ganjil','tahun_ajaran'=>2024,'wali_kelas'=>'Siti Rahmah, M.Kom','created_at'=>now(),'updated_at'=>now()]);
        $si_a  = DB::table('kelas')->insertGetId(['kampus_id'=>$itbm,'nama'=>'Sistem Informasi A','kode'=>'SI-A','semester'=>'ganjil','tahun_ajaran'=>2024,'wali_kelas'=>'Hj. Fatimah, M.Si','created_at'=>now(),'updated_at'=>now()]);

        // ── Kelas STAIN (2) ──
        $pai_a = DB::table('kelas')->insertGetId(['kampus_id'=>$stain,'nama'=>'Pendidikan Agama Islam A','kode'=>'PAI-A','semester'=>'ganjil','tahun_ajaran'=>2024,'wali_kelas'=>'Ustadz Ridwan, Lc., M.A','created_at'=>now(),'updated_at'=>now()]);
        $es_a  = DB::table('kelas')->insertGetId(['kampus_id'=>$stain,'nama'=>'Ekonomi Syariah A','kode'=>'ES-A','semester'=>'ganjil','tahun_ajaran'=>2024,'wali_kelas'=>'Dr. Nur Hasanah, M.E','created_at'=>now(),'updated_at'=>now()]);

        // ── Mata Kuliah ITBM ──
        $mkAlgo  = DB::table('mata_kuliah')->insertGetId(['kampus_id'=>$itbm,'kelas_id'=>$ti_a,'kode'=>'TI101','nama'=>'Algoritma dan Pemrograman','sks'=>3,'jenis'=>'teori_praktikum','dosen'=>'Dr. Ahmad Fauzi, M.T','total_pertemuan'=>16,'created_at'=>now(),'updated_at'=>now()]);
        $mkBD    = DB::table('mata_kuliah')->insertGetId(['kampus_id'=>$itbm,'kelas_id'=>$ti_a,'kode'=>'TI102','nama'=>'Basis Data','sks'=>3,'jenis'=>'teori_praktikum','dosen'=>'Siti Rahmah, M.Kom','total_pertemuan'=>16,'created_at'=>now(),'updated_at'=>now()]);
        $mkWeb   = DB::table('mata_kuliah')->insertGetId(['kampus_id'=>$itbm,'kelas_id'=>$ti_b,'kode'=>'TI201','nama'=>'Pemrograman Web','sks'=>3,'jenis'=>'praktikum','dosen'=>'Hasan, M.T','total_pertemuan'=>16,'created_at'=>now(),'updated_at'=>now()]);
        // ── Mata Kuliah STAIN ──
        $mkFiqih = DB::table('mata_kuliah')->insertGetId(['kampus_id'=>$stain,'kelas_id'=>$pai_a,'kode'=>'PAI201','nama'=>'Fiqih Kontemporer','sks'=>2,'jenis'=>'teori','dosen'=>'Ustadz Ridwan, Lc., M.A','total_pertemuan'=>16,'created_at'=>now(),'updated_at'=>now()]);
        $mkEkon  = DB::table('mata_kuliah')->insertGetId(['kampus_id'=>$stain,'kelas_id'=>$es_a,'kode'=>'ES101','nama'=>'Ekonomi Islam Dasar','sks'=>3,'jenis'=>'teori','dosen'=>'Dr. Nur Hasanah, M.E','total_pertemuan'=>16,'created_at'=>now(),'updated_at'=>now()]);

        // ── Mahasiswa ITBM TI-A (5 mhs) ──
        $mhsITBM = [];
        $dataMhsITBM = [
            ['ITBM2024001','Andi Baso Rahmat','L'],['ITBM2024002','Siti Aisyah Putri','P'],
            ['ITBM2024003','Muhammad Ridwan','L'],['ITBM2024004','Nurul Hikmah Sari','P'],
            ['ITBM2024005','Zulkifli Hakim','L'],
        ];
        foreach ($dataMhsITBM as [$nim,$nama,$jk]) {
            $mhsITBM[] = DB::table('mahasiswa')->insertGetId(['kampus_id'=>$itbm,'kelas_id'=>$ti_a,'nim'=>$nim,'nama'=>$nama,'jenis_kelamin'=>$jk,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Mahasiswa STAIN PAI-A (3 mhs) ──
        $mhsSTAIN = [];
        $dataMhsSTAIN = [
            ['STAIN2024001','Ahmad Maulana','L'],['STAIN2024002','Rahma Fitri Handayani','P'],['STAIN2024003','Hamzah Al-Rasyid','L'],
        ];
        foreach ($dataMhsSTAIN as [$nim,$nama,$jk]) {
            $mhsSTAIN[] = DB::table('mahasiswa')->insertGetId(['kampus_id'=>$stain,'kelas_id'=>$pai_a,'nim'=>$nim,'nama'=>$nama,'jenis_kelamin'=>$jk,'status'=>'aktif','created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Pendaftaran ──
        foreach ($mhsITBM as $id) {
            foreach ([$mkAlgo,$mkBD] as $mkId) {
                DB::table('pendaftaran_mahasiswa')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkId,'tahun_ajaran'=>2024,'semester'=>'ganjil','status'=>'aktif','created_at'=>now(),'updated_at'=>now()]);
            }
        }
        foreach ($mhsSTAIN as $id) {
            DB::table('pendaftaran_mahasiswa')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkFiqih,'tahun_ajaran'=>2024,'semester'=>'ganjil','status'=>'aktif','created_at'=>now(),'updated_at'=>now()]);
        }

        // ── Absensi 16 Pertemuan ──
        $statusPola = ['H','H','H','T','H','H','S','H','H','H','H','I','H','H','H','A'];
        foreach ($mhsITBM as $idx => $id) {
            for ($p=1; $p<=16; $p++) {
                $st = $statusPola[($p+$idx) % 16];
                DB::table('absensi')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkAlgo,'pertemuan_ke'=>$p,'tanggal'=>Carbon::now()->subWeeks(16-$p)->toDateString(),'status'=>$st,'created_at'=>now(),'updated_at'=>now()]);
            }
        }

        // ── Nilai Teori & Praktikum (Algoritma) ──
        $naData = [[80,85,78,82,83],[90,92,88,91,87],[70,72,65,68,74],[85,88,80,84,79],[75,78,72,76,81]];
        foreach ($mhsITBM as $idx => $id) {
            $n = $naData[$idx];
            $naTeori = round(($n[0]*0.20)+($n[1]*0.20)+($n[2]*0.25)+($n[3]*0.35), 2);
            DB::table('nilai_teori')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkAlgo,'keaktifan'=>$n[0],'tugas'=>$n[1],'uts'=>$n[2],'uas'=>$n[3],'nilai_akhir_teori'=>$naTeori,'created_at'=>now(),'updated_at'=>now()]);
            DB::table('nilai_praktikum')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkAlgo,'nilai_praktikum'=>$n[4],'created_at'=>now(),'updated_at'=>now()]);

            // Hitung nilai akhir
            $naFinal = round(($naTeori*0.5)+($n[4]*0.5),2);
            $poin    = 0;
            $absensi = DB::table('absensi')->where('mahasiswa_id',$id)->where('mata_kuliah_id',$mkAlgo)->get();
            $bobot   = ['H'=>2,'T'=>1,'S'=>1,'I'=>0,'A'=>0];
            foreach ($absensi as $a) $poin += $bobot[$a->status] ?? 0;
            $persen  = round(($poin/(16*2))*100, 2);
            $huruf   = match(true){$naFinal>=85=>'A',$naFinal>=75=>'B',$naFinal>=65=>'C',$naFinal>=55=>'D',default=>'E'};
            $status  = $persen>=75&&$naFinal>=55?'lulus':'tidak_lulus';
            DB::table('nilai_akhir')->insert(['mahasiswa_id'=>$id,'mata_kuliah_id'=>$mkAlgo,'nilai_teori'=>$naTeori,'nilai_praktikum'=>$n[4],'nilai_akhir'=>$naFinal,'huruf_mutu'=>$huruf,'persentase_kehadiran'=>$persen,'poin_kehadiran'=>$poin,'status_kelulusan'=>$status,'keterangan_gagal'=>$status=='tidak_lulus'?"Kehadiran {$persen}%":null,'created_at'=>now(),'updated_at'=>now()]);
        }

        $this->command->info('✅ Seeder OK: 2 kampus, 5 kelas, 5 matkul, 8 mahasiswa, data lengkap.');
        $this->command->table(['Email','Password','Kampus'],[
            ['admin@penilaian.ac.id','password','ITBM (superadmin)'],
            ['admin@itbm.ac.id','password','ITBM'],
            ['admin@stain.ac.id','password','STAIN'],
        ]);
    }
}
