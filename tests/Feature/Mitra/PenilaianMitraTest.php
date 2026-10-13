<?php

namespace Tests\Feature\Mitra;

use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Pendaftaran;
use App\Models\PenilaianDosen;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S2-06 (FR-11) — Mitra menilai peserta programnya per rubrik bersama,
 * finalisasi mengunci nilai, auto-selesai saat nilai dosen juga final.
 */
class PenilaianMitraTest extends TestCase
{
    use RefreshDatabase;

    /** Pendaftaran berjalan di program milik mitra tertentu. */
    private function mitraSetup(): array
    {
        $mitra = Mitra::factory()->create();
        $mhs = Mahasiswa::factory()->create();
        $program = Program::factory()->create(['mitra_id' => $mitra->id]);

        $pendaftaran = Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create([
                'mahasiswa_id' => $mhs->id,
                'program_id' => $program->id,
            ]);

        return [$mitra, $pendaftaran];
    }

    private function nilaiValid(): array
    {
        return ['nilai' => [
            'kedisiplinan' => 80, 'penguasaan' => 85,
            'kerjasama' => 90, 'laporan' => 75,
        ]];
    }

    public function test_mitra_melihat_daftar_peserta_programnya(): void
    {
        $this->withoutVite();
        [$mitra, $pendaftaran] = $this->mitraSetup();

        $res = $this->actingAs($mitra->user)->get(route('mitra.penilaian.index'));

        $res->assertOk()->assertSee($pendaftaran->mahasiswa->user->name);
    }

    public function test_tidak_bisa_menilai_program_mitra_lain(): void
    {
        $this->withoutVite();
        [$mitra, $pendaftaran] = $this->mitraSetup();
        $mitraLain = Mitra::factory()->create();

        $this->actingAs($mitraLain->user)
            ->get(route('mitra.penilaian.edit', $pendaftaran))
            ->assertForbidden();

        $this->actingAs($mitraLain->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $this->nilaiValid())
            ->assertForbidden();
    }

    public function test_draf_tersimpan_belum_final(): void
    {
        [$mitra, $pendaftaran] = $this->mitraSetup();

        $this->actingAs($mitra->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $this->nilaiValid())
            ->assertRedirect();

        $this->assertDatabaseCount('penilaian_mitras', 4);
        $this->assertDatabaseHas('penilaian_mitras', [
            'pendaftaran_id' => $pendaftaran->id,
            'komponen' => 'kedisiplinan',
            'nilai' => 80,
            'is_final' => false,
        ]);
        $this->assertSame(Pendaftaran::STATUS_BERJALAN, $pendaftaran->fresh()->status);
    }

    public function test_final_menunggu_nilai_dosen(): void
    {
        [$mitra, $pendaftaran] = $this->mitraSetup();

        $this->actingAs($mitra->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $this->nilaiValid() + ['final' => true])
            ->assertRedirect();

        $this->assertSame(4, $pendaftaran->penilaianMitras()->where('is_final', true)->count());
        // Dosen belum final → status tetap berjalan.
        $this->assertSame(Pendaftaran::STATUS_BERJALAN, $pendaftaran->fresh()->status);
    }

    public function test_nilai_final_terkunci(): void
    {
        [$mitra, $pendaftaran] = $this->mitraSetup();

        $this->actingAs($mitra->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $this->nilaiValid() + ['final' => true])
            ->assertRedirect();

        $turun = $this->nilaiValid();
        $turun['nilai']['kedisiplinan'] = 10;

        $this->actingAs($mitra->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $turun)
            ->assertForbidden();

        $this->assertSame(80, $pendaftaran->penilaianMitras()->where('komponen', 'kedisiplinan')->first()->nilai);
    }

    public function test_auto_selesai_saat_kedua_nilai_final(): void
    {
        [$mitra, $pendaftaran] = $this->mitraSetup();

        // Nilai dosen final dulu (4 komponen).
        foreach (['kedisiplinan', 'penguasaan', 'kerjasama', 'laporan'] as $k) {
            PenilaianDosen::create([
                'pendaftaran_id' => $pendaftaran->id,
                'komponen' => $k,
                'nilai' => 85,
                'is_final' => true,
                'difinalisasi_at' => now(),
            ]);
        }

        $this->actingAs($mitra->user)
            ->put(route('mitra.penilaian.update', $pendaftaran), $this->nilaiValid() + ['final' => true])
            ->assertRedirect();

        $this->assertSame(Pendaftaran::STATUS_SELESAI, $pendaftaran->fresh()->status);
    }
}
