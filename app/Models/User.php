<?php namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name','email','password','role','kampus_id'];
    protected $hidden   = ['password','remember_token'];

    public function kampus() { return $this->belongsTo(Kampus::class); }

    // Kampus yang sedang aktif dipilih (dari session atau kolom)
    public function getKampusAktifAttribute(): ?Kampus {
        $kid = session('kampus_id') ?? $this->kampus_id;
        return $kid ? Kampus::find($kid) : null;
    }
}
