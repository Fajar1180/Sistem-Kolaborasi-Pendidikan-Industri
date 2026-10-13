<?php

namespace Tests\Feature\Mahasiswa;

use App\Events\PendaftaranStatusBerubah;
use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * S1-09 (FR-04) — Pengajuan penempatan: isi formulir, lampiran berkas, ajukan,
 * revisi/ajukan ulang, serta akses berkas privat (NFR-08).
 */
class PengajuanTest extends TestCase
{
    use RefreshDatabase;

    private function mahasiswa(): Mahasiswa
    {
        return Mahasiswa::factory()->create();
    }

    private function pendaftaran(array $attributes = []): Pendaftaran
    {
        return Pendaftaran::factory()->create($attributes);
    }

    private function dokumen(Pendaftaran $pendaftaran, string $nama, bool $simpan = true): Dokumen
    {
        $path = 'dokumen-pengajuan/'.$pendaftaran->id.'/'.$nama;
        if ($simpan) {
            Storage::disk('local')->put($path, 'isi berkas');
        }

        return $pendaftaran->dokumens()->create([
            'nama' => $nama,
            'path' => $path,
            'tipe' => strtolower(pathinfo($nama, PATHINFO_EXTENSION)),
            'ukuran' => 10,
        ]);
    }

    public function test_pemilik_dapat_membuka_form_pengajuan(): void
    {
        $m = $this->mahasiswa();
        $program = Program::factory()->create(['judul' => 'Magang Data']);
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id, 'program_id' => $program->id]);

        $this->actingAs($m->user)->get(route('mahasiswa.pengajuan.edit', $pendaftaran))
            ->assertStatus(200)
            ->assertSee('Pengajuan Penempatan')
            ->assertSee('Magang Data')
            ->assertSee('Ajukan Penempatan');
    }

    public function test_mahasiswa_lain_tidak_dapat_membuka_form(): void
    {
        $a = $this->mahasiswa();
        $b = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $b->id]);

        $this->actingAs($a->user)->get(route('mahasiswa.pengajuan.edit', $pendaftaran))
            ->assertStatus(403);
    }

    public function test_mahasiswa_dapat_menyimpan_draf_dengan_berkas_dan_catatan(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id]);

        $this->actingAs($m->user)->put(route('mahasiswa.pengajuan.update', $pendaftaran), [
            'catatan_mahasiswa' => 'Saya berminat pada bidang data.',
            'files' => [
                UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
                UploadedFile::fake()->image('portofolio.png'),
            ],
        ])->assertRedirect(route('mahasiswa.pengajuan.edit', $pendaftaran));

        $pendaftaran->refresh();
        $this->assertSame('Saya berminat pada bidang data.', $pendaftaran->catatan_mahasiswa);
        $this->assertSame('draf', $pendaftaran->status);
        $this->assertCount(2, $pendaftaran->dokumens);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'simpan_pengajuan', 'entitas_id' => $pendaftaran->id]);

        foreach ($pendaftaran->dokumens as $dokumen) {
            Storage::disk('local')->assertExists($dokumen->path);
        }
    }

    public function test_berkas_dengan_tipe_tidak_diizinkan_ditolak(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id]);

        $this->actingAs($m->user)->put(route('mahasiswa.pengajuan.update', $pendaftaran), [
            'files' => [UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload')],
        ])->assertSessionHasErrors('files.0');

        $this->assertCount(0, $pendaftaran->refresh()->dokumens);
    }

    public function test_mahasiswa_dapat_menghapus_berkas(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id]);
        $dokumen = $this->dokumen($pendaftaran, 'lama.pdf');

        $this->actingAs($m->user)->put(route('mahasiswa.pengajuan.update', $pendaftaran), [
            'hapus_dokumen' => [$dokumen->id],
        ])->assertRedirect(route('mahasiswa.pengajuan.edit', $pendaftaran));

        $this->assertDatabaseMissing('dokumens', ['id' => $dokumen->id]);
        Storage::disk('local')->assertMissing($dokumen->path);
    }

    public function test_mahasiswa_dapat_mengajukan_penempatan(): void
    {
        Event::fake([PendaftaranStatusBerubah::class]);
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id, 'status' => 'draf']);

        $this->actingAs($m->user)->post(route('mahasiswa.pengajuan.ajukan', $pendaftaran))
            ->assertRedirect(route('mahasiswa.pendaftaran.show', $pendaftaran));

        $pendaftaran->refresh();
        $this->assertSame('diajukan', $pendaftaran->status);
        $this->assertNotNull($pendaftaran->diajukan_at);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'ajukan_pendaftaran', 'entitas_id' => $pendaftaran->id]);
        Event::assertDispatched(PendaftaranStatusBerubah::class);
    }

    public function test_tidak_dapat_mengajukan_dua_kali(): void
    {
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id, 'status' => 'diajukan', 'diajukan_at' => now()]);

        $this->actingAs($m->user)->post(route('mahasiswa.pengajuan.ajukan', $pendaftaran))
            ->assertRedirect(route('mahasiswa.pendaftaran.show', $pendaftaran))
            ->assertSessionHas('error');

        $this->assertSame('diajukan', $pendaftaran->refresh()->status);
    }

    public function test_pengajuan_yang_sudah_diajukan_tidak_dapat_diubah(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id, 'status' => 'diajukan', 'diajukan_at' => now()]);

        $this->actingAs($m->user)->put(route('mahasiswa.pengajuan.update', $pendaftaran), [
            'catatan_mahasiswa' => 'coba ubah',
            'files' => [UploadedFile::fake()->create('x.pdf', 10, 'application/pdf')],
        ])->assertRedirect(route('mahasiswa.pendaftaran.show', $pendaftaran))
            ->assertSessionHas('error');

        $pendaftaran->refresh();
        $this->assertNull($pendaftaran->catatan_mahasiswa);
        $this->assertCount(0, $pendaftaran->dokumens);
    }

    public function test_perlu_revisi_dapat_diedit_dan_diajukan_ulang(): void
    {
        Event::fake([PendaftaranStatusBerubah::class]);
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran([
            'mahasiswa_id' => $m->id, 'status' => 'perlu_revisi', 'catatan' => 'Lampiran kurang.',
        ]);

        $this->actingAs($m->user)->get(route('mahasiswa.pengajuan.edit', $pendaftaran))
            ->assertStatus(200)->assertSee('Lampiran kurang.');

        $this->actingAs($m->user)->post(route('mahasiswa.pengajuan.ajukan', $pendaftaran))
            ->assertRedirect(route('mahasiswa.pendaftaran.show', $pendaftaran));

        $this->assertSame('diajukan', $pendaftaran->refresh()->status);
    }

    public function test_pemilik_dapat_mengunduh_berkas(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id]);
        $dokumen = $this->dokumen($pendaftaran, 'cv.pdf');

        $this->actingAs($m->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(200);
    }

    public function test_mahasiswa_lain_tidak_dapat_mengunduh_berkas(): void
    {
        Storage::fake('local');
        $a = $this->mahasiswa();
        $b = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $b->id]);
        $dokumen = $this->dokumen($pendaftaran, 'cv.pdf');

        $this->actingAs($a->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(403);
    }

    public function test_mitra_penyelenggara_dan_admin_dapat_mengunduh_berkas(): void
    {
        Storage::fake('local');
        $program = Program::factory()->create();
        $pendaftaran = $this->pendaftaran(['program_id' => $program->id]);
        $dokumen = $this->dokumen($pendaftaran, 'cv.pdf');

        $this->actingAs($program->mitra->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(200);
        $this->actingAs(User::factory()->admin()->create())->get(route('dokumen.unduh', $dokumen))->assertStatus(200);
    }

    public function test_dosen_pembimbing_terkait_dapat_mengunduh_dosen_lain_tidak(): void
    {
        Storage::fake('local');
        $dosen = Dosen::factory()->create();
        $lain = Dosen::factory()->create();
        $pendaftaran = $this->pendaftaran(['dosen_pembimbing_id' => $dosen->id]);
        $dokumen = $this->dokumen($pendaftaran, 'cv.pdf');

        $this->actingAs($dosen->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(200);
        $this->actingAs($lain->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(403);
    }

    public function test_unduh_berkas_hilang_mengembalikan_404(): void
    {
        Storage::fake('local');
        $m = $this->mahasiswa();
        $pendaftaran = $this->pendaftaran(['mahasiswa_id' => $m->id]);
        $dokumen = $this->dokumen($pendaftaran, 'cv.pdf', simpan: false);

        $this->actingAs($m->user)->get(route('dokumen.unduh', $dokumen))->assertStatus(404);
    }
}
