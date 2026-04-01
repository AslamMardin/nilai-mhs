<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampus extends Model {
    protected $table = 'kampus';
    protected $fillable = ['nama','kode','alamat','telepon'];

    public function kelas(): HasMany { return $this->hasMany(Kelas::class); }
    public function mataKuliah(): HasMany { return $this->hasMany(MataKuliah::class); }
    public function mahasiswa(): HasMany { return $this->hasMany(Mahasiswa::class); }
}
