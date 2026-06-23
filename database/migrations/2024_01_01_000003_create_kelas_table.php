<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tahun_ajaran_id');
            $table->string('nama_kelas', 10);
            $table->tinyInteger('tingkat');
            $table->string('wali_kelas', 100)->nullable();
            $table->timestamps();

            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajaran')->onDelete('cascade');
            $table->index(['tahun_ajaran_id', 'tingkat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
