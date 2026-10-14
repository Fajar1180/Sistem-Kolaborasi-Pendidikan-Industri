<?php

namespace Tests\Feature\Admin;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S2-08 (FR-15, FR-16) — Admin: pengawasan, rekap, dan ekspor laporan.
 */
class RekapTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function seedData(): void
    {
        $mhs = Mahasiswa::factory()->create();
        $dosen = Dosen::factory()->create();

        Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create(['mahasiswa_id' => $mhs->id, 'dosen_pembimbing_id' => $dosen->id]);
    }

    public function test_admin_membuka_rekap_dengan_statistik(): void
    {
        $this->withoutVite();
        $this->seedData();

        $this->actingAs($this->admin())
            ->get(route('admin.rekap.index'))
            ->assertOk()
            ->assertSee('Pengawasan Program')
            ->assertSee('Total Pendaftaran');
    }

    public function test_filter_status_mempersempit_hasil(): void
    {
        $this->withoutVite();
        $this->seedData();

        $this->actingAs($this->admin())
            ->get(route('admin.rekap.index', ['status' => Pendaftaran::STATUS_SELESAI]))
            ->assertOk();
    }

    public function test_rekap_per_program_prodi_mitra(): void
    {
        $this->withoutVite();
        $this->seedData();

        foreach (['admin.rekap.program', 'admin.rekap.prodi', 'admin.rekap.mitra'] as $route) {
            $this->actingAs($this->admin())->get(route($route))->assertOk();
        }
    }

    public function test_ekspor_csv_mengembalikan_file(): void
    {
        $this->seedData();

        $res = $this->actingAs($this->admin())->get(route('admin.rekap.ekspor-excel'));
        $res->assertOk();
        $this->assertStringContainsString('text/csv', $res->headers->get('Content-Type'));
        $this->assertStringContainsString('Mahasiswa', $res->streamedContent());
    }

    public function test_non_admin_ditolak(): void
    {
        foreach (['dosen', 'mitra', 'mahasiswa'] as $peran) {
            $this->actingAs(User::factory()->create(['role' => $peran]))
                ->get(route('admin.rekap.index'))
                ->assertStatus(403);
        }
    }
}
