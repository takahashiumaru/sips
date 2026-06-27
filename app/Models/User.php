<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    /* ── Relations ── */

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'wali_murid_id');
    }

    public function pembayaranDicatat()
    {
        return $this->hasMany(Pembayaran::class, 'dicatat_oleh');
    }

    public function pembayaranDiverifikasi()
    {
        return $this->hasMany(Pembayaran::class, 'diverifikasi_oleh');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    /* ── Scopes ── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /* ── Helpers ── */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBendahara(): bool
    {
        return false;
    }

    public function isWaliMurid(): bool
    {
        return $this->role === 'wali_murid';
    }

    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    public function unreadNotifikasi()
    {
        return $this->notifikasi()->where('is_read', false);
    }
}
