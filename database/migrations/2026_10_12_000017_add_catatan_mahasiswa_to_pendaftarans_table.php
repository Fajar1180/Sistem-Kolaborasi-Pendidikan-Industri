<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Catatan/motivasi dari mahasiswa (kolom `catatan` dipakai untuk catatan reviewer).
            $table->text('catatan_mahasiswa')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('catatan_mahasiswa');
        });
    }
};
