<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    protected $table = 'kwitansi';

    protected $fillable = [
        'nomor_kwitansi',
        'pembayaran_id',
        'file_path',
        'dicetak_oleh',
        'dicetak_at',
    ];

    protected function casts(): array
    {
        return [
            'dicetak_at' => 'datetime',
        ];
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function dicetakOleh()
    {
        return $this->belongsTo(User::class, 'dicetak_oleh');
    }

    /**
     * Generate nomor kwitansi: KWT-{YYYY}-{MM}-{NNNNNN}
     */
    public static function generateNomor(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $prefix = "KWT-{$year}-{$month}-";

        $lastKwitansi = static::where('nomor_kwitansi', 'like', $prefix . '%')
            ->orderByDesc('nomor_kwitansi')
            ->first();

        if ($lastKwitansi) {
            $lastNumber = (int) substr($lastKwitansi->nomor_kwitansi, -6);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
