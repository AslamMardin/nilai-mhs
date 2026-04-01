<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {
    protected $table = 'kelas';
    protected $fillable = ['kampus_id','nama','kode','semester','tahun_ajaran','wali_kelas'];

    public function kampus() { return $this->belongsTo(Kampus::class); }
    public function mahasiswa() { return $this->hasMany(Mahasiswa::class); }
    public function mataKuliah() { return $this->hasMany(MataKuliah::class); }
}
