<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotNilai extends Model {
    protected $table = 'bobot_nilai';
    protected $fillable = [
        'kehadiran','akhlak','keaktifan','tugas','uts','uas'
    ];
}