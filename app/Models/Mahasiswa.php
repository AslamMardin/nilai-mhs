<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model {
    protected $table = 'mahasiswa';
    protected $fillable = ['kampus_id','kelas_id','nim','nama','jenis_kelamin','email','telepon','alamat','tanggal_lahir','tempat_lahir','status'];
    protected $casts = ['tanggal_lahir' => 'date'];

    public function kampus() { return $this->belongsTo(Kampus::class); }
    public function kelas()  { return $this->belongsTo(Kelas::class); }

    public function mataKuliah() {
        return $this->belongsToMany(MataKuliah::class, 'pendaftaran_mahasiswa', 'mahasiswa_id', 'mata_kuliah_id')
                    ->withPivot(['tahun_ajaran','semester','status'])->withTimestamps();
    }
    public function absensi()        { return $this->hasMany(Absensi::class); }
    public function nilaiTeori()     { return $this->hasMany(NilaiTeori::class); }
    public function nilaiPraktikum() { return $this->hasMany(NilaiPraktikum::class); }
    public function nilaiAkhir()     { return $this->hasMany(NilaiAkhir::class); }
}
