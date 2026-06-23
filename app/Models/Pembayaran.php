<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_bayar',
        'bukti_transfer',
        'status_verifikasi',
        'catatan_verifikasi',
        'dicatat_oleh',
        'diverifikasi_oleh',
        'diverifikasi_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
            'diverifikasi_at' => 'datetime',
        ];
    }

    public function tagihan()
    {
        return $this->belongsTo(TagihanSpp::class, 'tagihan_id');
    }

    public function kwitansi()
    {
        return $this->hasOne(Kwitansi::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function diverifikasiOleh()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    /* ── Helpers ── */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_verifikasi) {
            'terverifikasi' => 'Terverifikasi',
            'pending' => 'Menunggu Verifikasi',
            'ditolak' => 'Ditolak',
            default => $this->status_verifikasi,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_verifikasi) {
            'terverifikasi' => 'success',
            'pending' => 'warning',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return match ($this->metode_bayar) {
            'tunai' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            default => $this->metode_bayar,
        };
    }
}
