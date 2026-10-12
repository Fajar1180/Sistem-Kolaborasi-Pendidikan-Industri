<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_lupa_kata_sandi_dapat_ditampilkan(): void
    {
        $this->get('/forgot-password')->assertStatus(200);
    }

    public function test_tautan_atur_ulang_dapat_diminta(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_halaman_atur_ulang_dapat_ditampilkan(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $this->get('/reset-password/'.$notification->token)->assertStatus(200);

            return true;
        });
    }

    public function test_kata_sandi_dapat_diatur_ulang_dengan_token_valid(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'kata-sandi-baru',
                'password_confirmation' => 'kata-sandi-baru',
            ]);

            $response->assertSessionHasNoErrors();
            $response->assertRedirect(route('login'));

            return true;
        });
    }
}
