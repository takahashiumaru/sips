<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'foto',
        'kelas_id',
        'wali_murid_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliMurid()
    {
        return $this->belongsTo(User::class, 'wali_murid_id');
    }

    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSpp::class);
    }

    /* ── Scopes ── */

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /* ── Helpers ── */

    public function getTotalTunggakan(): float
    {
        return $this->tagihanSpp()
            ->where('status', '!=', 'lunas')
            ->sum(\Illuminate\Support\Facades\DB::raw('jumlah_tagihan - total_dibayar'));
    }
}
