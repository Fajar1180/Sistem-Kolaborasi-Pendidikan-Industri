<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('mitras')->cascadeOnDelete();
            $table->string('judul');
            $table->enum('jenis', ['kerja_praktik', 'magang', 'proyek_kolaborasi', 'kunjungan_industri', 'sertifikasi']);
            $table->text('deskripsi');
            $table->text('syarat')->nullable();
            $table->unsignedInteger('kuota')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_publikasi', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();

            $table->index('status_publikasi');
            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
