<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Pendaftaran;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * S1-04 — Matriks hak akses PRD 7.7 (minimal satu pengujian per baris tabel).
 */
class AccessMatrixTest extends TestCase
{
    use RefreshDatabase;

    private function userWithProfile(string $peran): User
    {
        $user = User::factory()->create(['role' => $peran]);

        if ($peran === 'mahasiswa') {
            Mahasiswa::factory()->create(['user_id' => $user->id]);
        }

        if ($peran === 'mitra') {
            Mitra::factory()->create(['user_id' => $user->id]);
        }

        return $user->fresh();
    }

    public function test_baris_peluang_program_mitra_mengelola_miliknya_peran_lain_melihat(): void
    {
        $mitraA = $this->userWithProfile('mitra');
        $mitraB = $this->userWithProfile('mitra');
        $mahasiswa = $this->userWithProfile('mahasiswa');

        $programA = Program::factory()->create(['mitra_id' => $mitraA->mitra->id, 'status_publikasi' => 'published']);

        $this->assertTrue(Gate::forUser($mitraA)->allows('create', Program::class));
        $this->assertTrue(Gate::forUser($mitraA)->allows('update', $programA));
        $this->assertFalse(Gate::forUser($mitraB)->allows('update', $programA));

        // Peran lain hanya boleh melihat, tidak boleh mengelola.
        $this->assertFalse(Gate::forUser($mahasiswa)->allows('create', Program::class));
        $this->assertFalse(Gate::forUser($mahasiswa)->allows('update', $programA));
        $this->assertTrue(Gate::forUser($mahasiswa)->allows('view', $programA));
    }

    public function test_baris_pendaftaran_mahasiswa_mendaftar_peran_lain_hanya_melihat(): void
    {
        $mahasiswa = $this->userWithProfile('mahasiswa');
        $mahasiswaLain = $this->userWithProfile('mahasiswa');
        $dosen = $this->userWithProfile('dosen');

        $pendaftaran = Pendaftaran::factory()->create(['mahasiswa_id' => $mahasiswa->mahasiswa->id]);

        $this->assertTrue(Gate::forUser($mahasiswa)->allows('create', Pendaftaran::class));
        $this->assertTrue(Gate::forUser($mahasiswa)->allows('update', $pendaftaran));
        $this->assertFalse(Gate::forUser($mahasiswaLain)->allows('update', $pendaftaran));

        $this->assertFalse(Gate::forUser($dosen)->allows('create', Pendaftaran::class));
        $this->assertTrue(Gate::forUser($dosen)->allows('view', $pendaftaran));
    }

    public function test_baris_data_master_dan_pengguna_hanya_admin(): void
    {
        foreach (['dosen', 'mitra', 'mahasiswa'] as $peran) {
            $user = $this->userWithProfile($peran);

            $this->actingAs($user)->get('/admin/users')->assertStatus(403);
        }

        $this->actingAs($this->userWithProfile('admin'))->get('/admin/users')->assertStatus(200);
    }

    public function test_akses_tanpa_hak_menghasilkan_403_dengan_halaman_ramah(): void
    {
        $mahasiswa = $this->userWithProfile('mahasiswa');

        $response = $this->actingAs($mahasiswa)->get('/admin/users');

        $response->assertStatus(403);
        $response->assertSee('Akses Ditolak');
    }

    public function test_akses_url_langsung_tanpa_menu_tetap_ditolak(): void
    {
        // Tidak lewat menu — langsung ketik URL area mitra sebagai mahasiswa.
        $this->actingAs($this->userWithProfile('mahasiswa'))
            ->get('/mitra/program')
            ->assertStatus(403);

        $this->actingAs($this->userWithProfile('mitra'))
            ->get('/dosen/pengajuan')
            ->assertStatus(403);
    }
}
