<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('uraian');
            $table->string('bukti_path')->nullable();
            $table->enum('status_tinjau', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->timestamps();

            $table->index('status_tinjau');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
