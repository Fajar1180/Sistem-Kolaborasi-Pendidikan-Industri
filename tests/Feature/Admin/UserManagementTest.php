<?php

namespace Tests\Feature\Admin;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\Pendaftaran;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * S1-06 (FR-14) — Admin mengelola pengguna.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_admin_dapat_membuka_daftar_pengguna(): void
    {
        $this->actingAs($this->admin())->get('/admin/users')->assertStatus(200)->assertSee('Pengelolaan Pengguna');
    }

    public function test_peran_selain_admin_ditolak(): void
    {
        foreach (['dosen', 'mitra', 'mahasiswa'] as $peran) {
            $this->actingAs(User::factory()->create(['role' => $peran]))
                ->get('/admin/users')
                ->assertStatus(403);
        }
    }

    public function test_admin_dapat_membuat_pengguna_mahasiswa_dengan_profil_otomatis(): void
    {
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);

        $this->actingAs($this->admin())->post('/admin/users', [
            'name' => 'Mahasiswa Baru',
            'email' => 'mahasiswa.baru@skp.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'mahasiswa',
            'nim' => '2024132777',
            'prodi' => 'Teknik Informatika',
            'angkatan' => 2024,
            'no_hp' => '081200001111',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'mahasiswa.baru@skp.test')->firstOrFail();

        $this->assertSame('mahasiswa', $user->role);
        $this->assertDatabaseHas('mahasiswas', ['user_id' => $user->id, 'nim' => '2024132777']);
    }

    public function test_pembuatan_mahasiswa_wajib_mengisi_nim_prodi_dan_angkatan(): void
    {
        $this->actingAs($this->admin())->post('/admin/users', [
            'name' => 'Tanpa NIM',
            'email' => 'tanpa.nim@skp.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'mahasiswa',
        ])->assertSessionHasErrors(['nim', 'prodi', 'angkatan']);

        $this->assertDatabaseMissing('users', ['email' => 'tanpa.nim@skp.test']);
    }

    public function test_admin_dapat_mengubah_data_pengguna_dan_profilnya(): void
    {
        $admin = $this->admin();
        $profil = Mahasiswa::factory()->create(['no_hp' => '081200000000']);
        $user = $profil->user;

        $this->actingAs($admin)->put('/admin/users/'.$user->id, [
            'name' => 'Nama Baru',
            'email' => $user->email,
            'nim' => $profil->nim,
            'prodi' => $profil->prodi,
            'angkatan' => $profil->angkatan,
            'no_hp' => '081299998888',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('Nama Baru', $user->fresh()->name);
        $this->assertSame('081299998888', $profil->fresh()->no_hp);
    }

    public function test_admin_dapat_menonaktifkan_dan_mengaktifkan_akun(): void
    {
        $admin = $this->admin();
        $user = User::factory()->dosen()->create(['is_active' => true]);

        $this->actingAs($admin)->patch('/admin/users/'.$user->id.'/toggle');

        $this->assertFalse($user->fresh()->is_active);

        $this->actingAs($admin)->patch('/admin/users/'.$user->id.'/toggle');

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_akun_nonaktif_tidak_dapat_masuk(): void
    {
        $admin = $this->admin();
        $user = User::factory()->dosen()->create();

        $this->actingAs($admin)->patch('/admin/users/'.$user->id.'/toggle');
        $this->post('/logout');

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_tidak_dapat_menonaktifkan_akun_sendiri(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->patch('/admin/users/'.$admin->id.'/toggle')
            ->assertSessionHasErrors('user');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_pengguna_dengan_relasi_tidak_boleh_dihapus(): void
    {
        $admin = $this->admin();
        $profil = Mahasiswa::factory()->create();
        Pendaftaran::factory()->create(['mahasiswa_id' => $profil->id]);

        $this->actingAs($admin)->delete('/admin/users/'.$profil->user_id)
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $profil->user_id]);
    }

    public function test_pengguna_tanpa_relasi_dapat_dihapus(): void
    {
        $admin = $this->admin();
        $user = User::factory()->admin()->create();

        $this->actingAs($admin)->delete('/admin/users/'.$user->id)
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_setiap_aksi_penting_tercatat_di_activity_logs(): void
    {
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Dosen Baru',
            'email' => 'dosen.baru@skp.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'dosen',
            'nidn' => '0401019999',
            'prodi' => 'Teknik Informatika',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'aksi' => 'buat_pengguna',
        ]);

        $target = User::where('email', 'dosen.baru@skp.test')->firstOrFail();

        $this->actingAs($admin)->patch('/admin/users/'.$target->id.'/toggle');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'aksi' => 'nonaktifkan_pengguna',
            'entitas_id' => $target->id,
        ]);
    }

    public function test_pencarian_dan_filter_peran_bekerja(): void
    {
        $admin = $this->admin();
        User::factory()->dosen()->create(['name' => 'Dosen Khusus']);
        User::factory()->mitra()->create(['name' => 'Mitra Lain']);

        $this->actingAs($admin)->get('/admin/users?q=Khusus')
            ->assertStatus(200)->assertSee('Dosen Khusus')->assertDontSee('Mitra Lain');

        $this->actingAs($admin)->get('/admin/users?peran=mitra')
            ->assertStatus(200)->assertSee('Mitra Lain')->assertDontSee('Dosen Khusus');
    }

    public function test_halaman_tambah_dan_ubah_pengguna_dapat_dirender(): void
    {
        $admin = $this->admin();
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);

        $this->actingAs($admin)->get('/admin/users/create')
            ->assertStatus(200)->assertSee('Tambah Pengguna')->assertSee('Data Mahasiswa');

        foreach ([
            Mahasiswa::factory()->create(),
            Dosen::factory()->create(),
            Mitra::factory()->create(),
        ] as $profil) {
            $this->actingAs($admin)->get('/admin/users/'.$profil->user_id.'/edit')
                ->assertStatus(200)->assertSee('Ubah Pengguna');
        }
    }
}
