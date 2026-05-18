<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTugasDetail extends Model
{
    protected $table = 'nilai_tugas_detail';
    protected $fillable = ['mahasiswa_id', 'mata_kuliah_id', 'nama_tugas', 'skor'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
