<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_masuk(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_dapat_mengakses_area_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
    }

    public function test_mahasiswa_tidak_dapat_mengakses_area_admin(): void
    {
        $mahasiswa = User::factory()->mahasiswa()->create();

        $this->actingAs($mahasiswa)->get('/admin/users')->assertStatus(403);
    }

    public function test_dosen_tidak_dapat_mengakses_area_mitra(): void
    {
        $dosen = User::factory()->dosen()->create();

        $this->actingAs($dosen)->get('/mitra/program')->assertStatus(403);
    }

    public function test_setiap_peran_dapat_mengakses_areanya_sendiri(): void
    {
        $this->actingAs(User::factory()->dosen()->create())->get('/dosen/pengajuan')->assertStatus(200);
        $this->actingAs(User::factory()->mitra()->create())->get('/mitra/program')->assertStatus(200);
        $this->actingAs(User::factory()->mahasiswa()->create())->get('/mahasiswa/program')->assertStatus(200);
    }

    public function test_dashboard_dapat_diakses_setelah_masuk(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertStatus(200);
    }

    public function test_pengguna_tidak_aktif_ditolak_dan_dikeluarkan(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
