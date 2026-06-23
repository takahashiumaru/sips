<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_spp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedInteger('tahun_ajaran_id');
            $table->unsignedTinyInteger('bulan');
            $table->year('tahun');
            $table->decimal('jumlah_tagihan', 12, 2);
            $table->decimal('total_dibayar', 12, 2)->default(0);
            $table->enum('status', ['belum_bayar', 'menunggu_verifikasi', 'lunas'])->default('belum_bayar');
            $table->date('jatuh_tempo');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->unique(['siswa_id', 'bulan', 'tahun'], 'uk_tagihan');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['status', 'bulan', 'tahun']);
            $table->index(['siswa_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_spp');
    }
};
