<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhir extends Model {
    protected $table = 'nilai_akhir';
    protected $fillable = ['mahasiswa_id','mata_kuliah_id','nilai_teori','nilai_praktikum','nilai_akhir','huruf_mutu','persentase_kehadiran','poin_kehadiran','status_kelulusan','keterangan_gagal'];
    protected $casts = ['nilai_teori'=>'float','nilai_praktikum'=>'float','nilai_akhir'=>'float','persentase_kehadiran'=>'float','poin_kehadiran'=>'integer'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class); }

    public static function toHuruf(float $n): string {
        return match(true) {
            $n >= 85 => 'A',
            $n >= 75 => 'B',
            $n >= 65 => 'C',
            $n >= 55 => 'D',
            default  => 'E',
        };
    }

    public function getBadgeClassAttribute(): string {
        return match($this->huruf_mutu) {
            'A'=>'badge-a','B'=>'badge-b','C'=>'badge-c','D'=>'badge-d', default=>'badge-e'
        };
    }
}
