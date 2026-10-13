<?php

namespace Tests\Feature\Mahasiswa;

use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S1-08 (FR-03) — Mendaftar program, cegah duplikat, cegah kuota penuh/closed, batalkan.
 */
class PendaftaranMahasiswaTest extends TestCase
{
    use RefreshDatabase;

    private function mahasiswa(): Mahasiswa
    {
        return Mahasiswa::factory()->create();
    }

    private function program(array $attributes = []): Program
    {
        return Program::factory()->create($attributes);
    }

    public function test_mahasiswa_dapat_mendaftar_program_published(): void
    {
        $mahasiswa = $this->mahasiswa();
        $program = $this->program(['judul' => 'Magang UI/UX']);

        $this->actingAs($mahasiswa->user)->get('/mahasiswa/program/'.$program->id.'/daftar')
            ->assertStatus(200)
            ->assertSee('Konfirmasi Pendaftaran')
            ->assertSee('Magang UI/UX');

        $this->actingAs($mahasiswa->user)->post('/mahasiswa/program/'.$program->id.'/daftar')
            ->assertRedirect(route('mahasiswa.pendaftaran.index'));

        $this->assertDatabaseHas('pendaftarans', [
            'mahasiswa_id' => $mahasiswa->id,
            'program_id' => $program->id,
            'status' => 'diajukan',
        ]);

        $pendaftaran = Pendaftaran::firstOrFail();
        $this->assertNotNull($pendaftaran->diajukan_at);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'daftar_program', 'entitas_id' => $pendaftaran->id]);
    }

    public function test_tidak_dapat_mendaftar_dua_kali_pada_program_sama(): void
    {
        $mahasiswa = $this->mahasiswa();
        $program = $this->program();
        Pendaftaran::factory()->create([
            'mahasiswa_id' => $mahasiswa->id, 'program_id' => $program->id, 'status' => 'diajukan',
        ]);

        $this->actingAs($mahasiswa->user)->post('/mahasiswa/program/'.$program->id.'/daftar')
            ->assertRedirect(route('mahasiswa.program.show', $program));

        $this->assertDatabaseCount('pendaftarans', 1);
    }

    public function test_tidak_dapat_mendaftar_program_kuota_penuh(): void
    {
        $mahasiswa = $this->mahasiswa();
        $program = $this->program(['kuota' => 1]);
        Pendaftaran::factory()->status('diterima_mitra')->create(['program_id' => $program->id]);

        $this->actingAs($mahasiswa->user)->post('/mahasiswa/program/'.$program->id.'/daftar')
            ->assertRedirect(route('mahasiswa.program.show', $program));

        $this->assertDatabaseMissing('pendaftarans', ['mahasiswa_id' => $mahasiswa->id]);
    }

    public function test_tidak_dapat_mendaftar_program_ditutup(): void
    {
        $mahasiswa = $this->mahasiswa();
        $closed = Program::factory()->closed()->create();

        $this->actingAs($mahasiswa->user)->get('/mahasiswa/program/'.$closed->id.'/daftar')->assertStatus(403);
        $this->actingAs($mahasiswa->user)->post('/mahasiswa/program/'.$closed->id.'/daftar')->assertStatus(403);

        $this->assertDatabaseCount('pendaftarans', 0);
    }

    public function test_pendaftaran_saya_menampilkan_status(): void
    {
        $mahasiswa = $this->mahasiswa();
        $program = $this->program(['judul' => 'Proyek Kolaborasi']);
        Pendaftaran::factory()->create([
            'mahasiswa_id' => $mahasiswa->id, 'program_id' => $program->id, 'status' => 'diajukan',
        ]);

        $this->actingAs($mahasiswa->user)->get('/mahasiswa/pendaftaran')
            ->assertStatus(200)
            ->assertSee('Proyek Kolaborasi')
            ->assertSee('Diajukan');
    }

    public function test_mahasiswa_hanya_melihat_pendaftaran_miliknya(): void
    {
        $a = $this->mahasiswa();
        $b = $this->mahasiswa();
        $programA = $this->program(['judul' => 'Punya A']);
        $programB = $this->program(['judul' => 'Punya B']);

        Pendaftaran::factory()->create(['mahasiswa_id' => $a->id, 'program_id' => $programA->id]);
        Pendaftaran::factory()->create(['mahasiswa_id' => $b->id, 'program_id' => $programB->id]);

        $this->actingAs($a->user)->get('/mahasiswa/pendaftaran')
            ->assertSee('Punya A')->assertDontSee('Punya B');
    }

    public function test_detail_pendaftaran_milik_mahasiswa_lain_ditolak(): void
    {
        $a = $this->mahasiswa();
        $b = $this->mahasiswa();
        $pendaftaran = Pendaftaran::factory()->create(['mahasiswa_id' => $b->id]);

        $this->actingAs($a->user)->get('/mahasiswa/pendaftaran/'.$pendaftaran->id)->assertStatus(403);
    }

    public function test_mahasiswa_dapat_membatalkan_pendaftaran(): void
    {
        $mahasiswa = $this->mahasiswa();
        $program = $this->program();
        $pendaftaran = Pendaftaran::factory()->create([
            'mahasiswa_id' => $mahasiswa->id, 'program_id' => $program->id, 'status' => 'diajukan',
        ]);

        $this->actingAs($mahasiswa->user)->delete('/mahasiswa/pendaftaran/'.$pendaftaran->id)
            ->assertRedirect(route('mahasiswa.pendaftaran.index'));

        $this->assertDatabaseMissing('pendaftarans', ['id' => $pendaftaran->id]);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'batal_pendaftaran', 'entitas_id' => $pendaftaran->id]);
    }

    public function test_pendaftaran_yang_sudah_diterima_tidak_dapat_dibatalkan(): void
    {
        $mahasiswa = $this->mahasiswa();
        $pendaftaran = Pendaftaran::factory()->status('diterima_mitra')->create(['mahasiswa_id' => $mahasiswa->id]);

        $this->actingAs($mahasiswa->user)->delete('/mahasiswa/pendaftaran/'.$pendaftaran->id)
            ->assertRedirect(route('mahasiswa.pendaftaran.show', $pendaftaran));

        $this->assertDatabaseHas('pendaftarans', ['id' => $pendaftaran->id]);
    }

    public function test_peran_selain_mahasiswa_tidak_dapat_mendaftar(): void
    {
        $program = $this->program();

        foreach (['admin', 'dosen', 'mitra'] as $peran) {
            $this->actingAs(User::factory()->create(['role' => $peran]))
                ->post('/mahasiswa/program/'.$program->id.'/daftar')
                ->assertStatus(403);
        }

        $this->assertDatabaseCount('pendaftarans', 0);
    }
}
