<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_masuk(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_semua_peran_dapat_membuka_halaman_akun_sendiri(): void
    {
        foreach (['admin', 'dosen', 'mitra', 'mahasiswa'] as $peran) {
            $user = User::factory()->create(['role' => $peran]);

            $this->actingAs($user)->get('/profile')->assertStatus(200);
        }
    }

    public function test_pengguna_dapat_mengubah_namanya_sendiri(): void
    {
        $user = User::factory()->create(['name' => 'Nama Lama']);

        $this->actingAs($user)->put('/profile', ['name' => 'Nama Baru']);

        $this->assertSame('Nama Baru', $user->fresh()->name);
    }

    public function test_pengguna_dapat_mengubah_kata_sandinya_sendiri(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'password' => 'kata-sandi-baru',
            'password_confirmation' => 'kata-sandi-baru',
        ]);

        $this->assertTrue(Hash::check('kata-sandi-baru', $user->fresh()->password));
    }

    public function test_pengguna_tidak_dapat_mengubah_perannya_sendiri(): void
    {
        $user = User::factory()->mahasiswa()->create();

        $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru',
            'role' => 'admin',
            'is_active' => false,
        ]);

        $user->refresh();

        $this->assertSame('mahasiswa', $user->role);
        $this->assertTrue($user->is_active);
    }

    public function test_kata_sandi_baru_harus_dikonfirmasi(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'password' => 'kata-sandi-baru',
            'password_confirmation' => 'beda-sendiri',
        ])->assertSessionHasErrors('password');
    }
}
