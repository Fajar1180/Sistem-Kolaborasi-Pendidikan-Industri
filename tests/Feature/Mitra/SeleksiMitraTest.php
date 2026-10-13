<?php

namespace Tests\Feature\Mitra;

use App\Events\PendaftaranStatusBerubah;
use App\Models\ActivityLog;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * S2-02 (FR-12) — Mitra menyeleksi & memverifikasi pelamar: terima (dibatasi
 * kuota) / tolak (wajib alasan), akses kepemilikan, dan transisi ke `berjalan`.
 */
class SeleksiMitraTest extends TestCase
{
    use RefreshDatabase;

    private function program(int $kuota = 3, array $attrs = []): Program
    {
        return Program::factory()->create(array_merge(['kuota' => $kuota], $attrs));
    }

    private function pelamar(Program $program, array $attrs = []): Pendaftaran
    {
        return Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_DISETUJUI_DOSEN)
            ->create(array_merge([
                'program_id' => $program->id,
                'dosen_pembimbing_id' => Dosen::factory()->create()->id,
                'disetujui_dosen_at' => now()->subDay(),
            ], $attrs));
    }

    private function mahasiswaBernama(string $nama): Mahasiswa
    {
        return Mahasiswa::factory()->create([
            'user_id' => User::factory()->create(['name' => $nama])->id,
        ]);
    }

    public function test_mitra_melihat_pelamar_disetujui_dosen_pada_programnya(): void
    {
        $program = $this->program();
        $pelamar = $this->pelamar($program, ['mahasiswa_id' => $this->mahasiswaBernama('Pelamar Tampil')->id]);
        $bukan = Pendaftaran::factory()->status(Pendaftaran::STATUS_DIAJUKAN)->create([
            'program_id' => $program->id,
            'mahasiswa_id' => $this->mahasiswaBernama('Bukan Pelamar')->id,
        ]);

        $resp = $this->actingAs($program->mitra->user)->get(route('mitra.program.pelamar', $program));

        $resp->assertOk();
        $resp->assertSee('Pelamar Tampil');
        $resp->assertDontSee('Bukan Pelamar');
    }

    public function test_mitra_lain_tidak_dapat_membuka_antrean_program_orang_lain(): void
    {
        $program = $this->program();
        $lain = Mitra::factory()->create();

        $this->actingAs($lain->user)
            ->get(route('mitra.program.pelamar', $program))
            ->assertForbidden();
    }

    public function test_peran_selain_mitra_ditolak(): void
    {
        $program = $this->program();

        $this->actingAs(User::factory()->mahasiswa()->create())
            ->get(route('mitra.program.pelamar', $program))->assertForbidden();

        $this->actingAs(User::factory()->dosen()->create())
            ->get(route('mitra.program.pelamar', $program))->assertForbidden();
    }

    public function test_mitra_dapat_menerima_pelamar(): void
    {
        Event::fake([PendaftaranStatusBerubah::class]);
        $program = $this->program();
        $p = $this->pelamar($program);

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.terima', $p))
            ->assertRedirect();

        $p->refresh();
        $this->assertSame(Pendaftaran::STATUS_DITERIMA_MITRA, $p->status);
        $this->assertNotNull($p->diterima_mitra_at);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'terima_peserta', 'entitas_id' => $p->id]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id,
            'judul' => 'Diterima mitra',
        ]);
        Event::assertDispatched(PendaftaranStatusBerubah::class);
    }

    public function test_kuota_tidak_dapat_terlampaui(): void
    {
        $program = $this->program(1);
        Pendaftaran::factory()->status(Pendaftaran::STATUS_DITERIMA_MITRA)->create(['program_id' => $program->id]);
        $p = $this->pelamar($program);

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.terima', $p))
            ->assertSessionHas('error');

        $this->assertSame(Pendaftaran::STATUS_DISETUJUI_DOSEN, $p->refresh()->status);
        $this->assertSame(1, $program->kuotaTerpakai());
    }

    public function test_penerimaan_mengisi_kuota_lalu_sisanya_tidak_dapat_diterima(): void
    {
        $program = $this->program(1);
        $a = $this->pelamar($program);
        $b = $this->pelamar($program);

        $this->actingAs($program->mitra->user)->post(route('mitra.pelamar.terima', $a));

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.terima', $b))
            ->assertSessionHas('error');

        $this->assertSame(Pendaftaran::STATUS_DITERIMA_MITRA, $a->refresh()->status);
        $this->assertSame(Pendaftaran::STATUS_DISETUJUI_DOSEN, $b->refresh()->status);
        $this->assertSame(1, $program->kuotaTerpakai());
    }

    public function test_terima_ganda_tidak_menggandakan_penerimaan(): void
    {
        $program = $this->program(2);
        $p = $this->pelamar($program);
        $user = $program->mitra->user;

        $this->actingAs($user)->post(route('mitra.pelamar.terima', $p))->assertRedirect();
        // Keputusan kedua ditolak kebijakan (status bukan lagi disetujui_dosen).
        $this->actingAs($user)->post(route('mitra.pelamar.terima', $p))->assertForbidden();

        $this->assertSame(1, $program->kuotaTerpakai());
        $this->assertSame(
            1,
            ActivityLog::where('entitas_id', $p->id)->where('aksi', 'terima_peserta')->count()
        );
    }

    public function test_tolak_wajib_alasan(): void
    {
        $program = $this->program();
        $p = $this->pelamar($program);

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.tolak', $p), ['catatan' => ''])
            ->assertSessionHasErrors('catatan');

        $this->assertSame(Pendaftaran::STATUS_DISETUJUI_DOSEN, $p->refresh()->status);
    }

    public function test_mitra_dapat_menolak_dengan_alasan(): void
    {
        $program = $this->program();
        $p = $this->pelamar($program);

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.tolak', $p), ['catatan' => 'Kualifikasi tidak sesuai'])
            ->assertRedirect();

        $p->refresh();
        $this->assertSame(Pendaftaran::STATUS_DITOLAK, $p->status);
        $this->assertSame('Kualifikasi tidak sesuai', $p->catatan);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'tolak_peserta', 'entitas_id' => $p->id]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id,
            'judul' => 'Ditolak mitra',
        ]);
    }

    public function test_mitra_lain_tidak_dapat_memutuskan(): void
    {
        $program = $this->program();
        $p = $this->pelamar($program);
        $lain = Mitra::factory()->create();

        $this->actingAs($lain->user)->post(route('mitra.pelamar.terima', $p))->assertForbidden();
        $this->actingAs($lain->user)->post(route('mitra.pelamar.tolak', $p), ['catatan' => 'x'])->assertForbidden();

        $this->assertSame(Pendaftaran::STATUS_DISETUJUI_DOSEN, $p->refresh()->status);
    }

    public function test_hanya_status_disetujui_dosen_yang_dapat_diseleksi(): void
    {
        $program = $this->program();
        $p = Pendaftaran::factory()->status(Pendaftaran::STATUS_DIAJUKAN)->create(['program_id' => $program->id]);

        $this->actingAs($program->mitra->user)
            ->post(route('mitra.pelamar.terima', $p))
            ->assertForbidden();

        $this->assertSame(Pendaftaran::STATUS_DIAJUKAN, $p->refresh()->status);
    }

    public function test_scheduler_mengubah_diterima_mitra_menjadi_berjalan_saat_mulai(): void
    {
        $program = $this->program(3, [
            'tanggal_mulai' => now()->subDay()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
        ]);
        $p = Pendaftaran::factory()->status(Pendaftaran::STATUS_DITERIMA_MITRA)->create(['program_id' => $program->id]);

        Artisan::call('program:mulai-berjalan');

        $this->assertSame(Pendaftaran::STATUS_BERJALAN, $p->refresh()->status);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'mulai_berjalan', 'entitas_id' => $p->id]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id,
            'judul' => 'Program mulai berjalan',
        ]);
    }

    public function test_scheduler_tidak_mengubah_yang_belum_mulai(): void
    {
        $program = $this->program(3, [
            'tanggal_mulai' => now()->addWeek()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);
        $p = Pendaftaran::factory()->status(Pendaftaran::STATUS_DITERIMA_MITRA)->create(['program_id' => $program->id]);

        Artisan::call('program:mulai-berjalan');

        $this->assertSame(Pendaftaran::STATUS_DITERIMA_MITRA, $p->refresh()->status);
    }
}
