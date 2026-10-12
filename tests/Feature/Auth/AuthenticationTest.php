<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_masuk_dapat_ditampilkan(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_pengguna_dapat_masuk_dengan_kredensial_benar(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.users.index'));
    }

    public function test_setiap_peran_diarahkan_ke_halaman_pendarat_sesuai_perannya(): void
    {
        $tujuan = [
            'admin' => 'admin.users.index',
            'dosen' => 'dosen.pengajuan.index',
            'mitra' => 'mitra.program.index',
            'mahasiswa' => 'mahasiswa.program.index',
        ];

        foreach ($tujuan as $peran => $namaRute) {
            $user = User::factory()->create(['role' => $peran]);

            $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ])->assertRedirect(route($namaRute));

            $this->post('/logout');
        }
    }

    public function test_pengguna_tidak_aktif_tidak_dapat_masuk(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_pengguna_tidak_dapat_masuk_dengan_kata_sandi_salah(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'kata-sandi-salah',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_pesan_galat_masuk_bersifat_generik(): void
    {
        $user = User::factory()->create();

        // Kata sandi salah pada email yang ada.
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'kata-sandi-salah',
        ])->assertSessionHasErrors('email');

        // Email tidak terdaftar pun memakai pesan generik yang sama.
        $this->post('/login', [
            'email' => 'tidak.ada@skp.test',
            'password' => 'apa-saja',
        ])->assertSessionHasErrors('email');

        // Pesan tidak membocorkan "email tidak terdaftar vs password salah".
        $pesan = trans('auth.failed');

        $this->assertStringNotContainsString('tidak terdaftar', strtolower($pesan));
        $this->assertStringContainsString('tidak sesuai', strtolower($pesan));
    }

    public function test_masuk_terthrottle_setelah_lima_percobaan_gagal(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->from('/login')->post('/login', [
                'email' => $user->email,
                'password' => 'kata-sandi-salah',
            ])->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 harus ter-throttle dengan pesan berbahasa Indonesia.
        $this->from('/login')
            ->followingRedirects()
            ->post('/login', [
                'email' => $user->email,
                'password' => 'kata-sandi-salah',
            ])
            ->assertSee('Terlalu banyak percobaan');
    }

    public function test_pengguna_dapat_keluar(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
