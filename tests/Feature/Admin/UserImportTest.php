<?php

namespace Tests\Feature\Admin;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * S1-06 (FR-14) — Impor data awal pengguna dari CSV.
 */
class UserImportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function berkasCsv(string $isi, string $nama = 'pengguna.csv'): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'skp_').'.csv';
        file_put_contents($path, $isi);

        return new UploadedFile($path, $nama, 'text/csv', null, true);
    }

    public function test_halaman_impor_dan_template_dapat_diakses_admin(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/import')->assertStatus(200)->assertSee('Impor Data Pengguna');

        $this->actingAs($admin)->get('/admin/import/template')
            ->assertStatus(200)
            ->assertHeader('content-disposition');
    }

    public function test_peran_selain_admin_ditolak(): void
    {
        $this->actingAs(User::factory()->mitra()->create())->get('/admin/import')->assertStatus(403);
    }

    public function test_baris_valid_diimpor_beserta_profilnya(): void
    {
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);
        $admin = $this->admin();

        $csv = implode("\n", [
            'nama,email,role,nim,prodi,angkatan,no_hp,nidn,nama_perusahaan,alamat,kontak',
            'Impor Mahasiswa,mhs.impor@skp.test,mahasiswa,2024132555,Teknik Informatika,2024,081200002222,,,,',
            'Impor Dosen,dosen.impor@skp.test,dosen,,Teknik Informatika,,,0401018888,,',
        ]);

        $this->actingAs($admin)->post('/admin/import', ['file' => $this->berkasCsv($csv)])
            ->assertRedirect(route('admin.import.index'));

        $this->assertDatabaseHas('users', ['email' => 'mhs.impor@skp.test', 'role' => 'mahasiswa']);
        $this->assertDatabaseHas('mahasiswas', ['nim' => '2024132555']);
        $this->assertDatabaseHas('users', ['email' => 'dosen.impor@skp.test', 'role' => 'dosen']);
        $this->assertDatabaseHas('dosens', ['nidn' => '0401018888']);
    }

    public function test_header_tidak_valid_membatalkan_seluruh_impor(): void
    {
        $admin = $this->admin();

        $csv = implode("\n", [
            'nama,email,peran',
            'Tanpa Role,tanpa.role@skp.test,mahasiswa',
        ]);

        $this->actingAs($admin)->post('/admin/import', ['file' => $this->berkasCsv($csv)])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('users', 1); // hanya admin
    }

    public function test_baris_gagal_dilaporkan_tanpa_membatalkan_baris_valid(): void
    {
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);
        $admin = $this->admin();

        $csv = implode("\n", [
            'nama,email,role,nim,prodi,angkatan,no_hp,nidn,nama_perusahaan,alamat,kontak',
            'Valid Satu,valid.satu@skp.test,mahasiswa,2024132001,Teknik Informatika,2024,081200000001,,,,',
            'Tanpa NIM,gagal.satu@skp.test,mahasiswa,,Teknik Informatika,2024,081200000002,,,,',
        ]);

        $response = $this->actingAs($admin)->post('/admin/import', ['file' => $this->berkasCsv($csv)]);

        $response->assertRedirect(route('admin.import.index'));
        $response->assertSessionHas('impor_hasil', function (array $hasil) {
            return count($hasil['berhasil']) === 1 && count($hasil['gagal']) === 1;
        });

        $this->assertDatabaseHas('users', ['email' => 'valid.satu@skp.test']);
        $this->assertDatabaseMissing('users', ['email' => 'gagal.satu@skp.test']);
    }

    public function test_kata_sandi_awal_hasil_impor_dapat_dipakai_masuk(): void
    {
        ProgramStudi::factory()->create(['nama' => 'Teknik Informatika']);
        $admin = $this->admin();

        $csv = implode("\n", [
            'nama,email,role,nim,prodi,angkatan,no_hp,nidn,nama_perusahaan,alamat,kontak',
            'Login Impor,login.impor@skp.test,mahasiswa,2024132444,Teknik Informatika,2024,081200003333,,,,',
        ]);

        $this->actingAs($admin)->post('/admin/import', ['file' => $this->berkasCsv($csv)]);
        $this->post('/logout');

        $this->post('/login', ['email' => 'login.impor@skp.test', 'password' => 'password']);

        $this->assertAuthenticated();
    }
}
