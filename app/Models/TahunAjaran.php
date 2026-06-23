<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_akhir',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function tarifSpp()
    {
        return $this->hasMany(TarifSpp::class);
    }

    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSpp::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
