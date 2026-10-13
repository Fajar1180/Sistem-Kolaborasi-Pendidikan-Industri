<?php

namespace Tests\Feature\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S1-08 (FR-03) — Katalog peluang: published saja, pencarian, filter, pagination.
 */
class ProgramKatalogTest extends TestCase
{
    use RefreshDatabase;

    private function mahasiswa(): User
    {
        return Mahasiswa::factory()->create()->user;
    }

    public function test_mahasiswa_hanya_melihat_program_published(): void
    {
        $mitra = Mitra::factory()->create(['nama_perusahaan' => 'PT Maju']);
        Program::factory()->create(['mitra_id' => $mitra->id, 'judul' => 'Magang Terbuka']);
        Program::factory()->draft()->create(['mitra_id' => $mitra->id, 'judul' => 'Draf Tersembunyi']);
        Program::factory()->closed()->create(['mitra_id' => $mitra->id, 'judul' => 'Ditutup Tersembunyi']);

        $this->actingAs($this->mahasiswa())->get('/mahasiswa/program')
            ->assertStatus(200)
            ->assertSee('Magang Terbuka')
            ->assertDontSee('Draf Tersembunyi')
            ->assertDontSee('Ditutup Tersembunyi');
    }

    public function test_peran_selain_mahasiswa_ditolak(): void
    {
        foreach (['admin', 'dosen', 'mitra'] as $peran) {
            $this->actingAs(User::factory()->create(['role' => $peran]))
                ->get('/mahasiswa/program')
                ->assertStatus(403);
        }
    }

    public function test_pencarian_judul_dan_nama_mitra(): void
    {
        $mitraA = Mitra::factory()->create(['nama_perusahaan' => 'PT Alpha']);
        $mitraB = Mitra::factory()->create(['nama_perusahaan' => 'PT Beta']);
        Program::factory()->create(['mitra_id' => $mitraA->id, 'judul' => 'Magang Data']);
        Program::factory()->create(['mitra_id' => $mitraB->id, 'judul' => 'Proyek Web']);

        $user = $this->mahasiswa();

        $this->actingAs($user)->get('/mahasiswa/program?q=Data')
            ->assertStatus(200)->assertSee('Magang Data')->assertDontSee('Proyek Web');

        $this->actingAs($user)->get('/mahasiswa/program?q=Beta')
            ->assertStatus(200)->assertSee('Proyek Web')->assertDontSee('Magang Data');
    }

    public function test_filter_jenis_dan_periode(): void
    {
        $mitra = Mitra::factory()->create();
        Program::factory()->create([
            'mitra_id' => $mitra->id, 'judul' => 'KP Saja', 'jenis' => 'kerja_praktik',
            'tanggal_mulai' => '2026-11-01', 'tanggal_selesai' => '2027-01-31',
        ]);
        Program::factory()->create([
            'mitra_id' => $mitra->id, 'judul' => 'Magang Saja', 'jenis' => 'magang',
            'tanggal_mulai' => '2027-03-01', 'tanggal_selesai' => '2027-05-31',
        ]);

        $user = $this->mahasiswa();

        $this->actingAs($user)->get('/mahasiswa/program?jenis=magang')
            ->assertStatus(200)->assertSee('Magang Saja')->assertDontSee('KP Saja');

        $this->actingAs($user)->get('/mahasiswa/program?periode=2026-11')
            ->assertStatus(200)->assertSee('KP Saja')->assertDontSee('Magang Saja');

        // Filter tidak valid diabaikan tanpa error.
        $this->actingAs($user)->get('/mahasiswa/program?jenis=ngawur&periode=abc')
            ->assertStatus(200)->assertSee('KP Saja')->assertSee('Magang Saja');
    }

    public function test_pagination_sembilan_per_halaman(): void
    {
        $mitra = Mitra::factory()->create();
        Program::factory()->count(12)->create(['mitra_id' => $mitra->id]);

        $user = $this->mahasiswa();

        $this->actingAs($user)->get('/mahasiswa/program')
            ->assertStatus(200)
            ->assertViewHas('programs', fn ($p) => $p->count() === 9 && $p->total() === 12);

        $this->actingAs($user)->get('/mahasiswa/program?page=2')
            ->assertViewHas('programs', fn ($p) => $p->count() === 3);
    }

    public function test_detail_program_published_dapat_dilihat(): void
    {
        $mitra = Mitra::factory()->create(['nama_perusahaan' => 'PT Nusantara']);
        $program = Program::factory()->create(['mitra_id' => $mitra->id, 'judul' => 'Sertifikasi Cloud']);

        $this->actingAs($this->mahasiswa())->get('/mahasiswa/program/'.$program->id)
            ->assertStatus(200)
            ->assertSee('Sertifikasi Cloud')
            ->assertSee('PT Nusantara')
            ->assertSee('Daftar Program');
    }

    public function test_detail_program_belum_published_ditolak(): void
    {
        $draf = Program::factory()->draft()->create();

        $this->actingAs($this->mahasiswa())->get('/mahasiswa/program/'.$draf->id)->assertStatus(403);
    }

    public function test_mahasiswa_tanpa_profil_tetap_dapat_membuka_katalog(): void
    {
        Program::factory()->create(['judul' => 'Peluang Umum']);
        $user = User::factory()->mahasiswa()->create();

        $this->actingAs($user)->get('/mahasiswa/program')->assertStatus(200)->assertSee('Peluang Umum');
        $this->actingAs($user)->get('/mahasiswa/pendaftaran')->assertStatus(200);
    }
}
