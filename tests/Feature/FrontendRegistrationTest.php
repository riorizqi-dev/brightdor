<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FrontendRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_can_register_as_a_user(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('frontend.register.store'), [
                'name' => 'User BrightDor',
                'email' => 'user@example.test',
                'phone' => '0812333444555',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertRedirect(route('frontend.login.create'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'user@example.test')->first();

        $this->assertNotNull($user);
        $this->assertSame('couple', $user->user_type);
        $this->assertFalse($user->hasRole('vendor'));
    }
}