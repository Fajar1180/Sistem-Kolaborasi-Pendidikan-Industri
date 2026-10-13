<?php

namespace Tests\Feature\Dosen;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S2-05 (FR-09/FR-10) — Dosen mencatat bimbingan + memberi nilai rubrik
 * dan finalisasi; nilai final terkunci; auto-selesai saat mitra final.
 */
class BimbinganPenilaianTest extends TestCase
{
    use RefreshDatabase;

    /** Pendaftaran berjalan milik dosen pembimbing tertentu. */
    private function bimbinganSetup(): array
    {
        $dosen = Dosen::factory()->create();
        $mhs = Mahasiswa::factory()->create();
        $program = Program::factory()->create();

        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create([
                'mahasiswa_id' => $mhs->id,
                'program_id' => $program->id,
                'dosen_pembimbing_id' => $dosen->id,
            ]);

        return [$dosen, $pendaftaran];
    }

    private function nilaiValid(): array
    {
        return ['nilai' => [
            'kedisiplinan' => 80, 'penguasaan' => 85,
            'kerjasama' => 90, 'laporan' => 75,
        ]];
    }

    public function test_dosen_bisa_mencatat_bimbingan(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        $res = $this->actingAs($dosen->user)->post(
            route('dosen.bimbingan.store', $pendaftaran),
            ['catatan' => 'Mahasiswa progres 50%, arahan bab 3 diperbaiki.']
        );

        $res->assertRedirect(route('dosen.bimbingan.show', $pendaftaran));
        $this->assertDatabaseHas('bimbingans', [
            'pendaftaran_id' => $pendaftaran->id,
            'dosen_id' => $dosen->id,
        ]);
    }

    public function test_dosen_lain_tidak_bisa_mencatat(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();
        $asing = Dosen::factory()->create();

        $res = $this->actingAs($asing->user)->post(
            route('dosen.bimbingan.store', $pendaftaran),
            ['catatan' => 'Catatan dari dosen yang tidak berhak sama sekali.']
        );

        $res->assertForbidden();
    }

    public function test_catatan_minimal_10_karakter(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        $res = $this->actingAs($dosen->user)->post(
            route('dosen.bimbingan.store', $pendaftaran),
            ['catatan' => 'pendek']
        );

        $res->assertSessionHasErrors('catatan');
    }

    public function test_dosen_bisa_simpan_draf_nilai(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        $res = $this->actingAs($dosen->user)->put(
            route('dosen.penilaian.update', $pendaftaran),
            $this->nilaiValid()
        );

        $res->assertRedirect();
        $this->assertDatabaseCount('penilaian_dosens', 4);
        $this->assertDatabaseHas('penilaian_dosens', [
            'pendaftaran_id' => $pendaftaran->id,
            'komponen' => 'penguasaan',
            'nilai' => 85,
            'is_final' => false,
        ]);
    }

    public function test_nilai_ditolak_jika_di_luar_0_100(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        $res = $this->actingAs($dosen->user)->put(
            route('dosen.penilaian.update', $pendaftaran),
            ['nilai' => [
                'kedisiplinan' => 80, 'penguasaan' => 150,
                'kerjasama' => 90, 'laporan' => 75,
            ]]
        );

        $res->assertSessionHasErrors('nilai.penguasaan');
    }

    public function test_finalisasi_mengunci_nilai(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        $this->actingAs($dosen->user)->put(
            route('dosen.penilaian.update', $pendaftaran),
            $this->nilaiValid() + ['final' => true]
        );

        // Coba ubah setelah final → 403.
        $res = $this->actingAs($dosen->user)->put(
            route('dosen.penilaian.update', $pendaftaran),
            ['nilai' => [
                'kedisiplinan' => 10, 'penguasaan' => 10,
                'kerjasama' => 10, 'laporan' => 10,
            ]]
        );

        $res->assertForbidden();
        $this->assertDatabaseHas('penilaian_dosens', [
            'pendaftaran_id' => $pendaftaran->id,
            'komponen' => 'kedisiplinan',
            'nilai' => 80,
            'is_final' => true,
        ]);
    }

    public function test_finalisasi_kedua_nilai_menyelesaikan_pendaftaran(): void
    {
        [$dosen, $pendaftaran] = $this->bimbinganSetup();

        // Mitra sudah final duluan (S2-06 pattern — langsung via model).
        foreach (['kedisiplinan', 'penguasaan', 'kerjasama', 'laporan'] as $k) {
            $pendaftaran->penilaianMitras()->create([
                'komponen' => $k, 'nilai' => 85,
                'is_final' => true, 'difinalisasi_at' => now(),
            ]);
        }

        $this->actingAs($dosen->user)->put(
            route('dosen.penilaian.update', $pendaftaran),
            $this->nilaiValid() + ['final' => true]
        );

        $this->assertSame(
            Pendaftaran::STATUS_SELESAI,
            $pendaftaran->fresh()->status
        );
    }
}
