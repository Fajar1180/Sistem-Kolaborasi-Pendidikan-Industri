<?php

namespace Tests\Feature\Dosen;

use App\Events\PendaftaranStatusBerubah;
use App\Models\ActivityLog;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * S2-01 (FR-08) — Persetujuan pengajuan penempatan oleh dosen pembimbing:
 * setujui/tolak/revisi, batas kepemilikan, idempotensi, dan penugasan pembimbing.
 */
class PersetujuanPengajuanTest extends TestCase
{
    use RefreshDatabase;

    private function pendaftaran(array $attrs = []): Pendaftaran
    {
        $mhs = Mahasiswa::factory()->create(['prodi' => 'Teknik Informatika']);
        $dosen = Dosen::factory()->create(['prodi' => 'Teknik Informatika']);
        $program = Program::factory()->create();

        return Pendaftaran::factory()->create(array_merge([
            'mahasiswa_id' => $mhs->id,
            'program_id' => $program->id,
            'dosen_pembimbing_id' => $dosen->id,
            'status' => Pendaftaran::STATUS_DIAJUKAN,
            'diajukan_at' => now(),
        ], $attrs));
    }

    public function test_dosen_hanya_melihat_pengajuan_yang_ditujukan_padanya(): void
    {
        $dosen = Dosen::factory()->create();
        $lain = Dosen::factory()->create();

        $milikSaya = Pendaftaran::factory()->create([
            'dosen_pembimbing_id' => $dosen->id, 'status' => 'diajukan', 'diajukan_at' => now(),
        ]);
        $milikLain = Pendaftaran::factory()->create([
            'dosen_pembimbing_id' => $lain->id, 'status' => 'diajukan', 'diajukan_at' => now(),
        ]);

        $resp = $this->actingAs($dosen->user)->get(route('dosen.pengajuan.index'));

        $resp->assertOk();
        $resp->assertSee($milikSaya->mahasiswa->user->name);
        $resp->assertDontSee($milikLain->mahasiswa->user->name);
    }

    public function test_filter_status_berfungsi(): void
    {
        $dosen = Dosen::factory()->create();
        Pendaftaran::factory()->create(['dosen_pembimbing_id' => $dosen->id, 'status' => 'diajukan', 'diajukan_at' => now()]);
        $revisi = Pendaftaran::factory()->create(['dosen_pembimbing_id' => $dosen->id, 'status' => 'perlu_revisi', 'diajukan_at' => now()]);

        $this->actingAs($dosen->user)
            ->get(route('dosen.pengajuan.index', ['filter' => 'perlu_revisi']))
            ->assertOk()->assertSee($revisi->mahasiswa->user->name);

        $this->actingAs($dosen->user)
            ->get(route('dosen.pengajuan.index', ['filter' => 'riwayat']))
            ->assertOk()->assertSee('Tidak ada pengajuan pada kategori ini.');
    }

    public function test_dosen_dapat_menyetujui_pengajuan(): void
    {
        Event::fake([PendaftaranStatusBerubah::class]);
        $p = $this->pendaftaran();

        $this->actingAs($p->dosenPembimbing->user)
            ->post(route('dosen.pengajuan.setujui', $p))
            ->assertRedirect(route('dosen.pengajuan.index'));

        $p->refresh();
        $this->assertSame('disetujui_dosen', $p->status);
        $this->assertNotNull($p->disetujui_dosen_at);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'setujui_pengajuan', 'entitas_id' => $p->id]);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id, 'judul' => 'Pengajuan disetujui dosen',
        ]);
        Event::assertDispatched(PendaftaranStatusBerubah::class);
    }

    public function test_tolak_wajib_alasan(): void
    {
        $p = $this->pendaftaran();

        $this->actingAs($p->dosenPembimbing->user)
            ->post(route('dosen.pengajuan.tolak', $p), ['catatan' => ''])
            ->assertSessionHasErrors('catatan');

        $this->assertSame('diajukan', $p->refresh()->status);
    }

    public function test_dosen_dapat_menolak_dengan_alasan(): void
    {
        $p = $this->pendaftaran();

        $this->actingAs($p->dosenPembimbing->user)
            ->post(route('dosen.pengajuan.tolak', $p), ['catatan' => 'Berkas tidak lengkap'])
            ->assertRedirect(route('dosen.pengajuan.index'));

        $p->refresh();
        $this->assertSame('ditolak', $p->status);
        $this->assertSame('Berkas tidak lengkap', $p->catatan);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id, 'judul' => 'Pengajuan ditolak',
        ]);
    }

    public function test_revisi_wajib_catatan_dan_mengubah_status(): void
    {
        $p = $this->pendaftaran();

        $this->actingAs($p->dosenPembimbing->user)
            ->post(route('dosen.pengajuan.revisi', $p), ['catatan' => ''])
            ->assertSessionHasErrors('catatan');

        $this->actingAs($p->dosenPembimbing->user)
            ->post(route('dosen.pengajuan.revisi', $p), ['catatan' => 'Tambahkan CV'])
            ->assertRedirect(route('dosen.pengajuan.index'));

        $p->refresh();
        $this->assertSame('perlu_revisi', $p->status);
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $p->mahasiswa->user_id, 'judul' => 'Pengajuan perlu revisi',
        ]);
    }

    public function test_aksi_ganda_idempoten(): void
    {
        $p = $this->pendaftaran();
        $dosenUser = $p->dosenPembimbing->user;

        $this->actingAs($dosenUser)->post(route('dosen.pengajuan.setujui', $p));
        $p->refresh();
        $this->assertSame('disetujui_dosen', $p->status);
        $jumlahLog = ActivityLog::where('entitas_id', $p->id)->where('aksi', 'setujui_pengajuan')->count();

        // Aksi kedua (refresh/back-button) tidak mengubah status dua kali.
        $this->actingAs($dosenUser)->post(route('dosen.pengajuan.setujui', $p))
            ->assertRedirect(route('dosen.pengajuan.show', $p));

        $this->assertSame('disetujui_dosen', $p->refresh()->status);
        $this->assertSame(
            $jumlahLog,
            ActivityLog::where('entitas_id', $p->id)->where('aksi', 'setujui_pengajuan')->count()
        );
    }

    public function test_dosen_lain_tidak_dapat_memutuskan(): void
    {
        $p = $this->pendaftaran();
        $lain = Dosen::factory()->create();

        $this->actingAs($lain->user)->get(route('dosen.pengajuan.show', $p))->assertForbidden();
        $this->actingAs($lain->user)->post(route('dosen.pengajuan.setujui', $p))->assertForbidden();
        $this->assertSame('diajukan', $p->refresh()->status);
    }

    public function test_peran_lain_tidak_dapat_mengakses_area_dosen(): void
    {
        foreach ([
            User::factory()->mahasiswa()->create(),
            User::factory()->mitra()->create(),
        ] as $u) {
            $this->actingAs($u)->get(route('dosen.pengajuan.index'))->assertForbidden();
        }
    }

    public function test_admin_dapat_melihat_semua_pengajuan_hanya_baca(): void
    {
        $a = $this->pendaftaran();
        $b = $this->pendaftaran();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.pengajuan.index'))
            ->assertOk()
            ->assertSee($a->mahasiswa->user->name)
            ->assertSee($b->mahasiswa->user->name);

        // Admin tidak berhak memutuskan (matriks §7.7: hanya dosen).
        $this->actingAs($admin)->post(route('dosen.pengajuan.setujui', $a))->assertForbidden();
        $this->assertSame('diajukan', $a->refresh()->status);
    }

    public function test_ajukan_menugaskan_dosen_pembimbing_sesuai_prodi(): void
    {
        $mhs = Mahasiswa::factory()->create(['prodi' => 'Sistem Informasi']);
        $dosen = Dosen::factory()->create(['prodi' => 'Sistem Informasi']);
        $program = Program::factory()->create();

        $p = Pendaftaran::factory()->create([
            'mahasiswa_id' => $mhs->id,
            'program_id' => $program->id,
            'dosen_pembimbing_id' => null,
            'status' => 'draf',
        ]);

        $this->actingAs($mhs->user)->post(route('mahasiswa.pengajuan.ajukan', $p));

        $this->assertSame($dosen->id, $p->refresh()->dosen_pembimbing_id);
    }

    public function test_halaman_detail_dosen_tampil_dengan_aksi(): void
    {
        $p = $this->pendaftaran();

        $this->actingAs($p->dosenPembimbing->user)
            ->get(route('dosen.pengajuan.show', $p))
            ->assertOk()
            ->assertSee($p->mahasiswa->user->name)
            ->assertSee(route('dosen.pengajuan.setujui', $p));
    }

    public function test_halaman_detail_admin_tampil_hanya_baca(): void
    {
        $p = $this->pendaftaran();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.pengajuan.show', $p))
            ->assertOk()
            ->assertSee($p->mahasiswa->user->name)
            ->assertSee('tidak menunggu keputusan')
            ->assertDontSee('/setujui');
    }
}
