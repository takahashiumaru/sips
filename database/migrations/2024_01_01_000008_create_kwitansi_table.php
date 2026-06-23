<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kwitansi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kwitansi', 30)->unique();
            $table->unsignedBigInteger('pembayaran_id')->unique();
            $table->string('file_path', 500)->nullable();
            $table->unsignedBigInteger('dicetak_oleh');
            $table->dateTime('dicetak_at');
            $table->timestamps();

            $table->foreign('pembayaran_id')->references('id')->on('pembayaran')->onDelete('cascade');
            $table->foreign('dicetak_oleh')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }
};
