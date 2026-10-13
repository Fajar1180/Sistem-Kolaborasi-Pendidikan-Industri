<?php

namespace Tests\Feature\Mitra;

use App\Models\Mitra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * S1-07 (FR-11) — Mitra mengelola profil perusahaannya sendiri.
 */
class ProfilMitraTest extends TestCase
{
    use RefreshDatabase;

    private function profil(): Mitra
    {
        return Mitra::factory()->create(['nama_perusahaan' => 'PT Contoh']);
    }

    public function test_mitra_dapat_membuka_form_profil(): void
    {
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->get('/mitra/profil')
            ->assertStatus(200)
            ->assertSee('Profil Perusahaan')
            ->assertSee('PT Contoh');
    }

    public function test_peran_selain_mitra_ditolak(): void
    {
        foreach (['admin', 'dosen', 'mahasiswa'] as $peran) {
            $this->actingAs(User::factory()->create(['role' => $peran]))
                ->get('/mitra/profil')
                ->assertStatus(403);
        }
    }

    public function test_mitra_dapat_memperbarui_profil(): void
    {
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Baru Sejahtera',
            'alamat' => 'Jl. Merdeka No. 1',
            'kontak' => '021123456',
            'deskripsi' => 'Perusahaan teknologi.',
        ])->assertRedirect(route('mitra.profil.edit'));

        $mitra->refresh();
        $this->assertSame('PT Baru Sejahtera', $mitra->nama_perusahaan);
        $this->assertSame('Jl. Merdeka No. 1', $mitra->alamat);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $mitra->user_id, 'aksi' => 'ubah_profil_mitra']);
    }

    public function test_nama_perusahaan_wajib_diisi(): void
    {
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', ['nama_perusahaan' => ''])
            ->assertSessionHasErrors('nama_perusahaan');
    }

    public function test_mitra_dapat_mengunggah_logo(): void
    {
        Storage::fake('public');
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Contoh',
            'logo' => UploadedFile::fake()->create('logo.png', 200, 'image/png'),
        ])->assertRedirect(route('mitra.profil.edit'));

        $mitra->refresh();
        $this->assertNotNull($mitra->logo_path);
        Storage::disk('public')->assertExists($mitra->logo_path);
    }

    public function test_logo_lebih_dari_lima_mb_ditolak(): void
    {
        Storage::fake('public');
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Contoh',
            'logo' => UploadedFile::fake()->create('besar.png', 6000, 'image/png'),
        ])->assertSessionHasErrors('logo');

        $this->assertNull($mitra->fresh()->logo_path);
    }

    public function test_logo_bertipe_salah_ditolak(): void
    {
        Storage::fake('public');
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Contoh',
            'logo' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('logo');

        $this->assertNull($mitra->fresh()->logo_path);
    }

    public function test_mengganti_logo_menghapus_berkas_lama(): void
    {
        Storage::fake('public');
        $mitra = $this->profil();

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Contoh',
            'logo' => UploadedFile::fake()->create('lama.png', 100, 'image/png'),
        ]);
        $lama = $mitra->fresh()->logo_path;

        $this->actingAs($mitra->user)->put('/mitra/profil', [
            'nama_perusahaan' => 'PT Contoh',
            'logo' => UploadedFile::fake()->create('baru.png', 100, 'image/png'),
        ]);
        $baru = $mitra->fresh()->logo_path;

        $this->assertNotSame($lama, $baru);
        Storage::disk('public')->assertMissing($lama);
        Storage::disk('public')->assertExists($baru);
    }
}
