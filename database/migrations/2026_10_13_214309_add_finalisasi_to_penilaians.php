<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penilaian_dosens', function (Blueprint $table) {
            $table->boolean('is_final')->default(false);
            $table->timestamp('difinalisasi_at')->nullable();
        });

        Schema::table('penilaian_mitras', function (Blueprint $table) {
            $table->boolean('is_final')->default(false);
            $table->timestamp('difinalisasi_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_dosens', function (Blueprint $table) {
            $table->dropColumn(['is_final', 'difinalisasi_at']);
        });

        Schema::table('penilaian_mitras', function (Blueprint $table) {
            $table->dropColumn(['is_final', 'difinalisasi_at']);
        });
    }
};
