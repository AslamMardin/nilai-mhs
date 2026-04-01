<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model {
    protected $table = 'absensi';
    protected $fillable = ['mahasiswa_id','mata_kuliah_id','pertemuan_ke','tanggal','status','keterangan'];
    protected $casts = ['tanggal' => 'date'];

    const BOBOT = ['H'=>2,'T'=>1,'S'=>1,'I'=>0,'A'=>0];
    const LABEL = ['H'=>'Hadir','T'=>'Terlambat','S'=>'Sakit','I'=>'Izin','A'=>'Absen'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class); }

    public static function hitungPoin(int $mahasiswaId, int $mkId): int {
        return (int) self::where('mahasiswa_id',$mahasiswaId)->where('mata_kuliah_id',$mkId)
            ->get()->sum(fn($a) => self::BOBOT[$a->status] ?? 0);
    }

    public static function hitungPersen(int $mahasiswaId, int $mkId, int $totalPertemuan=16): float {
        $maxPoin = $totalPertemuan * 2;
        if ($maxPoin === 0) return 0;
        return round((self::hitungPoin($mahasiswaId,$mkId) / $maxPoin) * 100, 2);
    }

    public function getBadgeAttribute(): string {
        return match($this->status) {
            'H'=>'success','T'=>'warning','S'=>'info','I'=>'secondary','A'=>'danger', default=>'light'
        };
    }
}
