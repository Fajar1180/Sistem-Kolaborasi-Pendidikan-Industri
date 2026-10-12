<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->cascadeOnDelete();
            $table->string('komponen');
            $table->unsignedTinyInteger('nilai');
            $table->timestamps();

            $table->unique(['pendaftaran_id', 'komponen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_mitras');
    }
};
