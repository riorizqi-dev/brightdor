<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(): User
    {
        return User::query()->firstOrCreate(
            ['email' => 'reset@brightdor.test'],
            [
                'name' => 'Reset User',
                'password' => Hash::make('old-password'),
                'user_type' => 'couple',
                'status' => 'active',
            ],
        );
    }

    public function test_guest_can_view_forgot_password_page(): void
    {
        $this->get(route('frontend.password.request'))
            ->assertOk()
            ->assertSee('Lupa Password');
    }

    public function test_reset_link_is_sent_to_existing_email(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('frontend.password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_link_rejects_unknown_email(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('frontend.password.email'), ['email' => 'tidak-ada@example.test'])
            ->assertSessionHasErrors('email');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = $this->makeUser();

        $token = Password::createToken($user);

        $this->get(route('frontend.password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertOk()
            ->assertSee('Atur Password Baru');

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('frontend.password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => 'new-secret-password',
                'password_confirmation' => 'new-secret-password',
            ])
            ->assertRedirect(route('frontend.login.create'));

        $this->assertTrue(Hash::check('new-secret-password', $user->fresh()->password));
    }

    public function test_reset_rejects_invalid_token(): void
    {
        $user = $this->makeUser();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('frontend.password.update'), [
                'token' => 'token-asal',
                'email' => $user->email,
                'password' => 'new-secret-password',
                'password_confirmation' => 'new-secret-password',
            ])
            ->assertSessionHasErrors('email');
    }
}
