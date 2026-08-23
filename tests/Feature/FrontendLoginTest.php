<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FrontendLoginTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('vendor');
    }

    private function makeUser(string $email, string $userType): User
    {
        return User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => ucfirst($userType).' User',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'user_type' => $userType,
                'status' => 'active',
            ],
        );
    }

    public function test_guest_can_view_login_page(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Masuk ke BrightDor')
            ->assertSee('Mendaftar sebagai Vendor');
    }

    public function test_admin_can_use_public_login_without_logging_in_twice(): void
    {
        $this->makeUser('admin@brightdor.test', 'admin');

        $this->post('/login', [
            'email' => 'admin@brightdor.test',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs(User::query()->where('email', 'admin@brightdor.test')->first());
    }

    public function test_vendor_is_redirected_to_vendor_panel(): void
    {
        $this->makeUser('vendor@brightdor.test', 'vendor');

        $this->post('/login', [
            'email' => 'vendor@brightdor.test',
            'password' => 'password',
        ])->assertRedirect('/vendor');

        $this->assertAuthenticated();
    }

    public function test_wrong_credentials_are_rejected(): void
    {
        $this->post('/login', [
            'email' => 'nobody@brightdor.test',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_couple_user_can_login_to_frontend(): void
    {
        $this->makeUser('couple@brightdor.test', 'couple');

        $this->post('/login', [
            'email' => 'couple@brightdor.test',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    public function test_logged_in_user_can_upgrade_to_vendor(): void
    {
        $couple = $this->makeUser('upgrade@brightdor.test', 'couple');

        $this->actingAs($couple)
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('vendors.register.store'), [
                'name' => 'Studio Upgrade',
                'phone' => '0812999888777',
            ])
            ->assertRedirect('/vendor');

        $couple->refresh();

        $this->assertSame('vendor', $couple->user_type);
        $this->assertTrue($couple->hasRole('vendor'));
    }
}
