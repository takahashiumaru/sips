<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_spp', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tahun_ajaran_id');
            $table->tinyInteger('tingkat');
            $table->decimal('jumlah', 12, 2);
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'tingkat'], 'uk_tarif');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_spp');
    }
};
