<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    protected $table = 'tagihan_spp';

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'jumlah_tagihan',
        'total_dibayar',
        'status',
        'jatuh_tempo',
        'catatan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_tagihan' => 'decimal:2',
            'total_dibayar' => 'decimal:2',
            'jatuh_tempo' => 'date',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ── Computed ── */

    public function getSisaTagihanAttribute(): float
    {
        return max(0, $this->jumlah_tagihan - $this->total_dibayar);
    }

    /* ── Scopes ── */

    public function scopeBelumLunas($query)
    {
        return $query->where('status', '!=', 'lunas');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBulanIni($query)
    {
        return $query->where('bulan', now()->month)->where('tahun', now()->year);
    }

    /* ── Helpers ── */

    public function getNamaBulanAttribute(): string
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulanNames[$this->bulan] ?? '';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'lunas' => 'Lunas',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'belum_bayar' => 'Belum Bayar',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'lunas' => 'success',
            'menunggu_verifikasi' => 'warning',
            'belum_bayar' => 'danger',
            default => 'secondary',
        };
    }
}
