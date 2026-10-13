<?php

namespace Tests\Feature\Notifikasi;

use App\Events\PendaftaranStatusBerubah;
use App\Listeners\KirimEmailStatusPenting;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Notifikasi;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * S2-07 (FR-17): notifikasi + komentar + email status penting.
 */
class NotifikasiKomentarTest extends TestCase
{
    use RefreshDatabase;

    private function pendaftaranBerjalan(): Pendaftaran
    {
        $dosen = Dosen::factory()->create();
        $mhs = Mahasiswa::factory()->create();

        return Pendaftaran::factory()
            ->status(Pendaftaran::STATUS_BERJALAN)
            ->create([
                'mahasiswa_id' => $mhs->id,
                'dosen_pembimbing_id' => $dosen->id,
            ]);
    }

    public function test_mahasiswa_mengirim_komentar_dan_dosen_dapat_notifikasi(): void
    {
        $p = $this->pendaftaranBerjalan();
        $mhsUser = $p->mahasiswa->user;
        $dosenUser = $p->dosenPembimbing->user;

        $this->actingAs($mhsUser)
            ->post(route('komentar.store', $p), ['isi' => 'Mohon bimbingan laporan minggu ini.'])
            ->assertRedirect();

        $this->assertDatabaseHas('komentars', [
            'pendaftaran_id' => $p->id,
            'user_id' => $mhsUser->id,
        ]);
        $this->assertDatabaseHas('notifikasis', ['user_id' => $dosenUser->id]);
        $this->assertDatabaseMissing('notifikasis', ['user_id' => $mhsUser->id]);
    }

    public function test_validasi_komentar_minimal_3_karakter(): void
    {
        $p = $this->pendaftaranBerjalan();

        $this->actingAs($p->mahasiswa->user)
            ->post(route('komentar.store', $p), ['isi' => 'ok'])
            ->assertSessionHasErrors('isi');
    }

    public function test_pihak_luar_tidak_bisa_melihat_komentar(): void
    {
        $p = $this->pendaftaranBerjalan();
        $asing = User::factory()->mahasiswa()->create();

        $this->actingAs($asing)->get(route('komentar.index', $p))->assertForbidden();
    }

    public function test_arsip_notifikasi_dan_tandai_dibaca(): void
    {
        $this->withoutVite();
        $user = User::factory()->mahasiswa()->create();
        Notifikasi::kirim($user, 'Judul uji', 'Isi uji coba notifikasi.');

        $this->actingAs($user)->get(route('notifikasi.index'))->assertOk()->assertSee('Judul uji');

        $notif = $user->notifikasis()->first();
        $this->actingAs($user)->post(route('notifikasi.baca', $notif))->assertRedirect();
        $this->assertNotNull($notif->fresh()->dibaca_at);
    }

    public function test_tidak_bisa_menandai_notifikasi_milik_orang_lain(): void
    {
        $a = User::factory()->mahasiswa()->create();
        $b = User::factory()->mahasiswa()->create();
        Notifikasi::kirim($b, 'Milik B', 'Isi untuk B.');
        $notif = $b->notifikasis()->first();

        $this->actingAs($a)->post(route('notifikasi.baca', $notif))->assertForbidden();
    }

    public function test_tandai_semua_dibaca_sekaligus(): void
    {
        $user = User::factory()->mahasiswa()->create();
        Notifikasi::kirim($user, 'Satu', 'Isi satu.');
        Notifikasi::kirim($user, 'Dua', 'Isi dua.');

        $this->actingAs($user)->post(route('notifikasi.baca-semua'))->assertRedirect();
        $this->assertEquals(0, $user->notifikasis()->belumDibaca()->count());
    }

    public function test_event_status_penting_terhubung_ke_listener(): void
    {
        Event::fake([PendaftaranStatusBerubah::class]);
        $p = $this->pendaftaranBerjalan();

        event(new PendaftaranStatusBerubah($p, Pendaftaran::STATUS_SELESAI));

        Event::assertDispatched(PendaftaranStatusBerubah::class);
        Event::assertListening(PendaftaranStatusBerubah::class, KirimEmailStatusPenting::class);
    }

    public function test_listener_mengirim_email_untuk_status_penting(): void
    {
        Mail::fake();
        $p = $this->pendaftaranBerjalan();

        (new KirimEmailStatusPenting)->handle(
            new PendaftaranStatusBerubah($p, Pendaftaran::STATUS_SELESAI)
        );

        Mail::assertSent(\App\Mail\StatusPendaftaranMail::class);
    }

    public function test_listener_diam_untuk_status_biasa(): void
    {
        Mail::fake();
        $p = $this->pendaftaranBerjalan();

        (new KirimEmailStatusPenting)->handle(
            new PendaftaranStatusBerubah($p, Pendaftaran::STATUS_BERJALAN)
        );

        Mail::assertNothingSent();
    }
}
