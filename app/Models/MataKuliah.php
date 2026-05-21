<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model {
    protected $table = 'mata_kuliah';
    protected $fillable = ['kampus_id','kelas_id','kode','nama','sks','jenis','dosen','total_pertemuan','tanggal_mulai','jam_mulai','jam_selesai'];

    public function kampus() { return $this->belongsTo(Kampus::class); }
    public function kelas()  { return $this->belongsTo(Kelas::class); }

    public function mahasiswa() {
        return $this->belongsToMany(Mahasiswa::class, 'pendaftaran_mahasiswa', 'mata_kuliah_id', 'mahasiswa_id')
                    ->withPivot(['tahun_ajaran','semester','status'])->withTimestamps();
    }
    public function absensi()        { return $this->hasMany(Absensi::class); }
    public function nilaiTeori()     { return $this->hasMany(NilaiTeori::class); }
    public function nilaiPraktikum() { return $this->hasMany(NilaiPraktikum::class); }
    public function nilaiAkhir()     { return $this->hasMany(NilaiAkhir::class); }

    public function hasTeori():bool     { return in_array($this->jenis, ['teori','teori_praktikum']); }
    public function hasPraktikum():bool { return in_array($this->jenis, ['praktikum','teori_praktikum']); }

    public function getLabelJenisAttribute():string {
        return match($this->jenis) {
            'teori'           => 'Teori',
            'praktikum'       => 'Praktikum',
            'teori_praktikum' => 'Teori + Praktikum',
            default           => $this->jenis,
        };
    }
}
