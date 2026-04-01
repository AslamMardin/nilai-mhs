<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NilaiPraktikum extends Model {
    protected $table = 'nilai_praktikum';
    protected $fillable = ['mahasiswa_id','mata_kuliah_id','nilai_praktikum'];
    protected $casts = ['nilai_praktikum'=>'float'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class); }
}
