<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->unsignedInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('wali_murid_id')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete();
            $table->foreign('wali_murid_id')->references('id')->on('users')->nullOnDelete();
            $table->index('status');
            $table->index('kelas_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
