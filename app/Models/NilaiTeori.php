<?php namespace App\Models;
use App\Models\BobotNilai;
use Illuminate\Database\Eloquent\Model;

class NilaiTeori extends Model {
    protected $table = 'nilai_teori';
    protected $fillable = ['mahasiswa_id','mata_kuliah_id','keaktifan','tugas','uts','uas','nilai_akhir_teori'];
    protected $casts = ['keaktifan'=>'float','tugas'=>'float','uts'=>'float','uas'=>'float','nilai_akhir_teori'=>'float'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class); }

    // NA = keaktifan*0.20 + tugas*0.20 + uts*0.25 + uas*0.35
    // public function hitung(): float {
    //     return round(($this->keaktifan*0.20)+($this->tugas*0.20)+($this->uts*0.25)+($this->uas*0.35), 2);
    // }


public function hitung(): float {
    $bobot = BobotNilai::first();
    
    // --- IDE 3: Leburkan Kehadiran ke dalam Nilai Keaktifan ---
    $mk = MataKuliah::find($this->mata_kuliah_id);
    $jumlahPertemuan = $mk && $mk->total_pertemuan > 0 ? $mk->total_pertemuan : 14;
    $persenHadir = Absensi::hitungPersen($this->mahasiswa_id, $this->mata_kuliah_id, $jumlahPertemuan);
    
    // Nilai dasar keaktifan dari kehadiran (max 70 jika hadir 100%)
    $baseKeaktifan = $persenHadir * 0.7;
    // Gabungkan nilai keaktifan inputan dosen dengan base kehadiran, maksimal 100
    $keaktifanFinal = min(100, $this->keaktifan + $baseKeaktifan);

    return round(
        ($keaktifanFinal * (($bobot->keaktifan ?? 20)/100)) +
        ($this->tugas     * (($bobot->tugas ?? 20)/100)) +
        ($this->uts       * (($bobot->uts ?? 25)/100)) +
        ($this->uas       * (($bobot->uas ?? 35)/100)),
    2);
}

    public static function simpan(array $d): self {
        $inst = self::updateOrCreate(
            ['mahasiswa_id'=>$d['mahasiswa_id'],'mata_kuliah_id'=>$d['mata_kuliah_id']],
            ['keaktifan'=>$d['keaktifan']??0,'tugas'=>$d['tugas']??0,'uts'=>$d['uts']??0,'uas'=>$d['uas']??0]
        );
        $inst->nilai_akhir_teori = $inst->hitung();
        $inst->save();
        return $inst;
    }
}
