<?php

namespace Tests\Feature\Mitra;

use App\Models\Mitra;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S1-07 (FR-11) — Mitra membuat, mengubah, mempublikasikan, dan menutup peluang.
 */
class ProgramMitraTest extends TestCase
{
    use RefreshDatabase;

    private function mitra(): Mitra
    {
        return Mitra::factory()->create();
    }

    private function payload(Program $program, array $ubah = []): array
    {
        return array_merge([
            'judul' => $program->judul,
            'jenis' => $program->jenis,
            'deskripsi' => $program->deskripsi,
            'syarat' => $program->syarat,
            'kuota' => $program->kuota,
            'tanggal_mulai' => $program->tanggal_mulai->toDateString(),
            'tanggal_selesai' => $program->tanggal_selesai->toDateString(),
        ], $ubah);
    }

    public function test_mitra_hanya_melihat_peluangnya_sendiri(): void
    {
        $a = $this->mitra();
        $b = $this->mitra();
        Program::factory()->create(['mitra_id' => $a->id, 'judul' => 'Peluang Milik A']);
        Program::factory()->draft()->create(['mitra_id' => $b->id, 'judul' => 'Peluang Milik B']);

        $this->actingAs($a->user)->get('/mitra/program')
            ->assertStatus(200)
            ->assertSee('Peluang Milik A')
            ->assertDontSee('Peluang Milik B');
    }

    public function test_peran_selain_mitra_ditolak(): void
    {
        $this->actingAs(User::factory()->mahasiswa()->create())
            ->get('/mitra/program')
            ->assertStatus(403);
    }

    public function test_mitra_dapat_membuat_peluang_baru_sebagai_draf(): void
    {
        $mitra = $this->mitra();

        $this->actingAs($mitra->user)->post('/mitra/program', [
            'judul' => 'Magang Data Analyst',
            'jenis' => 'magang',
            'deskripsi' => 'Magang analisis data.',
            'syarat' => 'IPK minimal 3.00',
            'kuota' => 2,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2027-01-31',
        ])->assertRedirect(route('mitra.program.index'));

        $program = Program::where('judul', 'Magang Data Analyst')->firstOrFail();

        $this->assertSame($mitra->id, $program->mitra_id);
        $this->assertSame('draft', $program->status_publikasi);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'buat_peluang', 'entitas_id' => $program->id]);
    }

    public function test_validasi_peluang_menolak_data_tidak_valid(): void
    {
        $mitra = $this->mitra();

        $this->actingAs($mitra->user)->post('/mitra/program', [
            'judul' => '',
            'jenis' => 'jenis_ngawur',
            'deskripsi' => '',
            'kuota' => 0,
            'tanggal_mulai' => '2027-01-31',
            'tanggal_selesai' => '2026-01-01',
        ])->assertSessionHasErrors(['judul', 'jenis', 'deskripsi', 'kuota', 'tanggal_selesai']);

        $this->assertDatabaseCount('programs', 0);
    }

    public function test_mitra_dapat_mengubah_peluangnya(): void
    {
        $mitra = $this->mitra();
        $program = Program::factory()->draft()->create(['mitra_id' => $mitra->id, 'kuota' => 2]);

        $this->actingAs($mitra->user)->put('/mitra/program/'.$program->id, $this->payload($program, [
            'judul' => 'Judul Setelah Diubah',
            'kuota' => 5,
        ]))->assertRedirect(route('mitra.program.index'));

        $this->assertSame('Judul Setelah Diubah', $program->fresh()->judul);
        $this->assertSame(5, $program->fresh()->kuota);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'ubah_peluang', 'entitas_id' => $program->id]);
    }

    public function test_mitra_tidak_dapat_mengubah_peluang_mitra_lain(): void
    {
        $a = $this->mitra();
        $b = $this->mitra();
        $program = Program::factory()->draft()->create(['mitra_id' => $b->id]);

        $this->actingAs($a->user)->get('/mitra/program/'.$program->id.'/edit')->assertStatus(403);
        $this->actingAs($a->user)->put('/mitra/program/'.$program->id, $this->payload($program))->assertStatus(403);
        $this->actingAs($a->user)->patch('/mitra/program/'.$program->id.'/publish')->assertStatus(403);
        $this->actingAs($a->user)->patch('/mitra/program/'.$program->id.'/close')->assertStatus(403);

        $this->assertSame('draft', $program->fresh()->status_publikasi);
    }

    public function test_mitra_dapat_mempublikasikan_dan_menutup_peluang(): void
    {
        $mitra = $this->mitra();
        $program = Program::factory()->draft()->create(['mitra_id' => $mitra->id]);

        $this->actingAs($mitra->user)->patch('/mitra/program/'.$program->id.'/publish')->assertRedirect();
        $this->assertSame('published', $program->fresh()->status_publikasi);

        $this->actingAs($mitra->user)->patch('/mitra/program/'.$program->id.'/close')->assertRedirect();
        $this->assertSame('closed', $program->fresh()->status_publikasi);

        $this->assertDatabaseHas('activity_logs', ['aksi' => 'publikasi_peluang', 'entitas_id' => $program->id]);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'tutup_peluang', 'entitas_id' => $program->id]);
    }

    public function test_kuota_tidak_boleh_kurang_dari_peserta_yang_sudah_diterima(): void
    {
        $mitra = $this->mitra();
        $program = Program::factory()->create(['mitra_id' => $mitra->id, 'kuota' => 3]);

        Pendaftaran::factory()->count(2)->status('diterima_mitra')->create(['program_id' => $program->id]);

        $this->assertSame(2, $program->kuotaTerpakai());

        $this->actingAs($mitra->user)->put('/mitra/program/'.$program->id, $this->payload($program, ['kuota' => 1]))
            ->assertSessionHasErrors('kuota');

        $this->assertSame(3, $program->fresh()->kuota);

        $this->actingAs($mitra->user)->put('/mitra/program/'.$program->id, $this->payload($program, ['kuota' => 2]))
            ->assertRedirect(route('mitra.program.index'));

        $this->assertSame(2, $program->fresh()->kuota);
    }

    public function test_hanya_peluang_published_yang_tampil_bagi_mahasiswa(): void
    {
        $mitra = $this->mitra();
        $published = Program::factory()->create(['mitra_id' => $mitra->id]);
        $draf = Program::factory()->draft()->create(['mitra_id' => $mitra->id]);
        $mahasiswa = User::factory()->mahasiswa()->create();

        $this->assertTrue($mahasiswa->can('view', $published));
        $this->assertFalse($mahasiswa->can('view', $draf));

        // Pemilik tetap dapat melihat drafnya sendiri.
        $this->assertTrue($mitra->user->can('view', $draf));
    }

    public function test_halaman_buat_dan_ubah_peluang_dapat_dirender(): void
    {
        $mitra = $this->mitra();
        $program = Program::factory()->draft()->create(['mitra_id' => $mitra->id]);

        $this->actingAs($mitra->user)->get('/mitra/program/create')
            ->assertStatus(200)->assertSee('Buat Peluang Baru');

        $this->actingAs($mitra->user)->get('/mitra/program/'.$program->id.'/edit')
            ->assertStatus(200)->assertSee('Ubah Peluang');
    }

    public function test_mitra_tanpa_profil_tetap_dapat_membuka_areanya(): void
    {
        // Akun mitra yang belum punya baris `mitras` tidak boleh terhalang 403.
        $user = User::factory()->mitra()->create();

        $this->actingAs($user)->get('/mitra/program')
            ->assertStatus(200)
            ->assertSee('belum memiliki profil perusahaan');

        $this->actingAs($user)->get('/mitra/program/create')
            ->assertRedirect(route('mitra.profil.edit'));
    }
}
