<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama_kelas',
        'tingkat',
        'wali_kelas',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->nama_kelas . ' (Tingkat ' . $this->tingkat . ')';
    }
}
