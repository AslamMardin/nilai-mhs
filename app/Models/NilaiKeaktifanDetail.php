<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKeaktifanDetail extends Model
{
    protected $table = 'nilai_keaktifan_detail';
    protected $fillable = ['mahasiswa_id', 'mata_kuliah_id', 'pertemuan_ke', 'skor', 'indikator'];

    protected $casts = [
        'indikator' => 'array'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
