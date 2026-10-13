<?php

namespace Tests\Feature\Mahasiswa;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S2-03 (FR-05) — Pencatatan kegiatan mahasiswa pada pendaftaran berjalan:
 * buat, ubah sebelum ditinjau, larangan setelah disetujui, kepemilikan.
 */
class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    private function mahasiswa(): Mahasiswa
    {
        return Mahasiswa::factory()->create();
    }

    private function pendaftaranBerjalan(Mahasiswa $mahasiswa): Pendaftaran
    {
        return Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create(['mahasiswa_id' => $mahasiswa->id]);
    }

    public function test_mahasiswa_bisa_mencatat_kegiatan_saat_berjalan(): void
    {
        $mhs = $this->mahasiswa();
        $pendaftaran = $this->pendaftaranBerjalan($mhs);

        $response = $this->actingAs($mhs->user)->post(
            route('mahasiswa.kegiatan.store', $pendaftaran),
            ['tanggal' => now()->toDateString(), 'uraian' => 'Orientasi dan pengenalan alat kerja.']
        );

        $response->assertRedirect(route('mahasiswa.kegiatan.index', $pendaftaran));
        $this->assertDatabaseHas('kegiatans', [
            'pendaftaran_id' => $pendaftaran->id,
            'uraian' => 'Orientasi dan pengenalan alat kerja.',
        ]);
    }

    public function test_tidak_bisa_mencatat_saat_belum_berjalan(): void
    {
        $mhs = $this->mahasiswa();
        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_DITERIMA_MITRA)
            ->create(['mahasiswa_id' => $mhs->id]);

        $this->actingAs($mhs->user)->post(
            route('mahasiswa.kegiatan.store', $pendaftaran),
            ['tanggal' => now()->toDateString(), 'uraian' => 'Coba catat.']
        )->assertForbidden();
    }

    public function test_bisa_mengubah_kegiatan_yang_belum_disetujui(): void
    {
        $mhs = $this->mahasiswa();
        $pendaftaran = $this->pendaftaranBerjalan($mhs);
        $kegiatan = Kegiatan::factory()->create(['pendaftaran_id' => $pendaftaran->id]);

        $this->actingAs($mhs->user)->put(
            route('mahasiswa.kegiatan.update', [$pendaftaran, $kegiatan]),
            ['tanggal' => now()->toDateString(), 'uraian' => 'Uraian diperbarui.']
        )->assertRedirect(route('mahasiswa.kegiatan.index', $pendaftaran));

        $this->assertDatabaseHas('kegiatans', ['id' => $kegiatan->id, 'uraian' => 'Uraian diperbarui.']);
    }

    public function test_tidak_bisa_mengubah_kegiatan_yang_sudah_disetujui(): void
    {
        $mhs = $this->mahasiswa();
        $pendaftaran = $this->pendaftaranBerjalan($mhs);
        $kegiatan = Kegiatan::factory()->create([
            'pendaftaran_id' => $pendaftaran->id,
            'status_tinjau' => 'disetujui',
        ]);

        $this->actingAs($mhs->user)->put(
            route('mahasiswa.kegiatan.update', [$pendaftaran, $kegiatan]),
            ['tanggal' => now()->toDateString(), 'uraian' => 'Coba ubah.']
        )->assertForbidden();
    }

    public function test_tidak_bisa_melihat_kegiatan_milik_orang_lain(): void
    {
        $pendaftaran = $this->pendaftaranBerjalan($this->mahasiswa());
        $asing = $this->mahasiswa();

        $this->actingAs($asing->user)
            ->get(route('mahasiswa.kegiatan.index', $pendaftaran))
            ->assertForbidden();
    }
}
