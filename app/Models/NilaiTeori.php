<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NilaiTeori extends Model {
    protected $table = 'nilai_teori';
    protected $fillable = ['mahasiswa_id','mata_kuliah_id','keaktifan','tugas','uts','uas','nilai_akhir_teori'];
    protected $casts = ['keaktifan'=>'float','tugas'=>'float','uts'=>'float','uas'=>'float','nilai_akhir_teori'=>'float'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class); }

    // NA = keaktifan*0.20 + tugas*0.20 + uts*0.25 + uas*0.35
    public function hitung(): float {
        return round(($this->keaktifan*0.20)+($this->tugas*0.20)+($this->uts*0.25)+($this->uas*0.35), 2);
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
