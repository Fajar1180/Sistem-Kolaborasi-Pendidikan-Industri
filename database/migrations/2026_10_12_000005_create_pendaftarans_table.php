<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->enum('status', [
                'draf', 'diajukan', 'perlu_revisi', 'disetujui_dosen',
                'ditolak', 'diterima_mitra', 'berjalan', 'selesai',
            ])->default('draf');
            $table->text('catatan')->nullable();
            $table->timestamp('diajukan_at')->nullable();
            $table->timestamp('disetujui_dosen_at')->nullable();
            $table->timestamp('diterima_mitra_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'program_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
