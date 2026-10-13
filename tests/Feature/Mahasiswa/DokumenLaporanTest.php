<?php

namespace Tests\Feature\Mahasiswa;

use App\Models\Dokumen;
use App\Models\Laporan;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * S2-04 (FR-06/FR-07) — Kelola dokumen & laporan final pada pendaftaran berjalan.
 */
class DokumenLaporanTest extends TestCase
{
    use RefreshDatabase;

    private function pendaftaranBerjalan(?callable $programMutator = null): array
    {
        $mhs = Mahasiswa::factory()->create();
        $program = Program::factory()->create(['tanggal_selesai' => now()->addDays(7)->toDateString()]);

        if ($programMutator) {
            $programMutator($program);
            $program->refresh();
        }

        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create(['mahasiswa_id' => $mhs->id, 'program_id' => $program->id]);

        return [$mhs, $pendaftaran];
    }

    private function pdf(): UploadedFile
    {
        return UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');
    }

    public function test_mahasiswa_bisa_mengunggah_dokumen_saat_berjalan(): void
    {
        Storage::fake('local');
        [$mhs, $pendaftaran] = $this->pendaftaranBerjalan();

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.dokumen.store', $pendaftaran),
            ['berkas' => $this->pdf(), 'nama' => 'Sertifikat']
        )->assertRedirect(route('mahasiswa.dokumen.index', $pendaftaran));

        $this->assertDatabaseHas('dokumens', [
            'pendaftaran_id' => $pendaftaran->id,
            'nama' => 'Sertifikat',
        ]);
    }

    public function test_tidak_bisa_unggah_dokumen_saat_belum_berjalan(): void
    {
        Storage::fake('local');
        $mhs = Mahasiswa::factory()->create();
        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_DITERIMA_MITRA)
            ->create(['mahasiswa_id' => $mhs->id]);

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.dokumen.store', $pendaftaran),
            ['berkas' => $this->pdf()]
        )->assertForbidden();
    }

    public function test_mahasiswa_bisa_mengganti_dan_menghapus_dokumen(): void
    {
        Storage::fake('local');
        [$mhs, $pendaftaran] = $this->pendaftaranBerjalan();
        $dokumen = Dokumen::factory()->create(['pendaftaran_id' => $pendaftaran->id]);

        $this->actingAs($mhs->user)->put(
            route('mahasiswa.dokumen.update', [$pendaftaran, $dokumen]),
            ['berkas' => $this->pdf(), 'nama' => 'Revisi']
        )->assertRedirect(route('mahasiswa.dokumen.index', $pendaftaran));

        $this->assertDatabaseHas('dokumens', ['id' => $dokumen->id, 'nama' => 'Revisi']);

        $this->actingAs($mhs->user)->delete(
            route('mahasiswa.dokumen.destroy', [$pendaftaran, $dokumen])
        )->assertRedirect(route('mahasiswa.dokumen.index', $pendaftaran));

        $this->assertDatabaseMissing('dokumens', ['id' => $dokumen->id]);
    }

    public function test_kumpul_laporan_tepat_waktu_tidak_terlambat(): void
    {
        Storage::fake('local');
        [$mhs, $pendaftaran] = $this->pendaftaranBerjalan();

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.laporan.store', $pendaftaran),
            ['berkas' => $this->pdf()]
        )->assertRedirect(route('mahasiswa.laporan.show', $pendaftaran));

        $this->assertDatabaseHas('laporans', [
            'pendaftaran_id' => $pendaftaran->id,
            'terlambat' => false,
            'status_tinjau' => 'menunggu',
        ]);
    }

    public function test_kumpul_laporan_setelah_tenggat_ditandai_terlambat(): void
    {
        Storage::fake('local');
        [$mhs, $pendaftaran] = $this->pendaftaranBerjalan(
            fn ($program) => $program->update(['tanggal_selesai' => now()->subDay()->toDateString()])
        );

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.laporan.store', $pendaftaran),
            ['berkas' => $this->pdf()]
        )->assertRedirect(route('mahasiswa.laporan.show', $pendaftaran));

        $this->assertDatabaseHas('laporans', [
            'pendaftaran_id' => $pendaftaran->id,
            'terlambat' => true,
        ]);
    }

    public function test_revisi_laporan_menimpa_bukan_duplikat(): void
    {
        Storage::fake('local');
        [$mhs, $pendaftaran] = $this->pendaftaranBerjalan();
        Laporan::factory()->create(['pendaftaran_id' => $pendaftaran->id, 'terlambat' => false]);

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.laporan.store', $pendaftaran),
            ['berkas' => $this->pdf()]
        )->assertRedirect(route('mahasiswa.laporan.show', $pendaftaran));

        $this->assertSame(1, Laporan::where('pendaftaran_id', $pendaftaran->id)->count());
    }

    public function test_tidak_bisa_kelola_dokumen_milik_orang_lain(): void
    {
        Storage::fake('local');
        $pemilik = Mahasiswa::factory()->create();
        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create(['mahasiswa_id' => $pemilik->id]);
        $asing = Mahasiswa::factory()->create();

        $this->actingAs($asing->user)->post(
            route('mahasiswa.dokumen.store', $pendaftaran),
            ['berkas' => $this->pdf()]
        )->assertForbidden();
    }
}
