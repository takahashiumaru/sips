<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->dateTime('tanggal_bayar');
            $table->enum('metode_bayar', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->string('bukti_transfer', 500)->nullable();
            $table->enum('status_verifikasi', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->unsignedBigInteger('dicatat_oleh');
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->dateTime('diverifikasi_at')->nullable();
            $table->timestamps();

            $table->foreign('tagihan_id')->references('id')->on('tagihan_spp')->onDelete('cascade');
            $table->foreign('dicatat_oleh')->references('id')->on('users');
            $table->foreign('diverifikasi_oleh')->references('id')->on('users');
            $table->index('tagihan_id');
            $table->index('tanggal_bayar');
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
