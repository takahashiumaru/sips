<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifSpp extends Model
{
    protected $table = 'tarif_spp';

    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat',
        'jumlah',
        'keterangan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
        ];
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
