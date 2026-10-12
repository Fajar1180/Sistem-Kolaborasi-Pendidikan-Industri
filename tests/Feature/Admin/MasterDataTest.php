<?php

namespace Tests\Feature\Admin;

use App\Models\PeriodeAkademik;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S1-06 — Admin mengelola data master.
 */
class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_peran_selain_admin_ditolak(): void
    {
        $this->actingAs(User::factory()->dosen()->create())
            ->get('/admin/master/program-studi')
            ->assertStatus(403);

        $this->actingAs(User::factory()->mahasiswa()->create())
            ->get('/admin/master/periode-akademik')
            ->assertStatus(403);
    }

    public function test_admin_dapat_menambah_mengubah_dan_menghapus_program_studi(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/master/program-studi', [
            'kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1',
        ])->assertRedirect();

        $prodi = ProgramStudi::where('kode', 'TI')->firstOrFail();
        $this->assertTrue($prodi->is_aktif);

        $this->actingAs($admin)->put('/admin/master/program-studi/'.$prodi->id, [
            'kode' => 'TI', 'nama' => 'Teknik Informatika (Revisi)', 'jenjang' => 'S1', 'is_aktif' => '0',
        ])->assertRedirect();

        $this->assertSame('Teknik Informatika (Revisi)', $prodi->fresh()->nama);
        $this->assertFalse($prodi->fresh()->is_aktif);

        $this->actingAs($admin)->delete('/admin/master/program-studi/'.$prodi->id);

        $this->assertDatabaseMissing('program_studis', ['id' => $prodi->id]);
    }

    public function test_kode_program_studi_harus_unik(): void
    {
        ProgramStudi::factory()->create(['kode' => 'TI']);

        $this->actingAs($this->admin())->post('/admin/master/program-studi', [
            'kode' => 'TI', 'nama' => 'Duplikat', 'jenjang' => 'S1',
        ])->assertSessionHasErrors('kode');
    }

    public function test_admin_dapat_mengelola_periode_akademik(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/master/periode-akademik', [
            'kode' => '2025/2026-1',
            'nama' => '2025/2026 Ganjil',
            'tanggal_mulai' => '2025-09-01',
            'tanggal_selesai' => '2026-01-31',
            'is_aktif' => '1',
        ])->assertRedirect();

        $periode = PeriodeAkademik::where('kode', '2025/2026-1')->firstOrFail();
        $this->assertTrue($periode->is_aktif);
    }

    public function test_tanggal_selesai_harus_setelah_tanggal_mulai(): void
    {
        $this->actingAs($this->admin())->post('/admin/master/periode-akademik', [
            'kode' => 'X',
            'nama' => 'Periode Salah',
            'tanggal_mulai' => '2026-01-31',
            'tanggal_selesai' => '2025-09-01',
        ])->assertSessionHasErrors('tanggal_selesai');
    }

    public function test_hanya_satu_periode_yang_aktif(): void
    {
        $admin = $this->admin();
        $lama = PeriodeAkademik::factory()->create(['kode' => '2025/2026-1', 'is_aktif' => true]);

        $this->actingAs($admin)->post('/admin/master/periode-akademik', [
            'kode' => '2026/2027-1',
            'nama' => '2026/2027 Ganjil',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2027-01-31',
            'is_aktif' => '1',
        ]);

        $this->assertFalse($lama->fresh()->is_aktif);
        $this->assertSame(1, PeriodeAkademik::where('is_aktif', true)->count());
    }

    public function test_data_master_tercatat_di_activity_logs(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/master/program-studi', [
            'kode' => 'SI', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'aksi' => 'buat_program_studi',
        ]);
    }

    public function test_halaman_data_master_dapat_dirender(): void
    {
        $admin = $this->admin();
        ProgramStudi::factory()->create(['kode' => 'TI', 'nama' => 'Teknik Informatika']);
        PeriodeAkademik::factory()->create(['is_aktif' => true]);

        $this->actingAs($admin)->get('/admin/master/program-studi')
            ->assertStatus(200)->assertSee('Program Studi')->assertSee('Teknik Informatika');

        $this->actingAs($admin)->get('/admin/master/periode-akademik')
            ->assertStatus(200)->assertSee('Periode Akademik');
    }
}
