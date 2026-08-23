<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\InvitationOrder;
use App\Models\InvitationTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicInvitationTest extends TestCase
{
    use DatabaseTransactions;

    private function makeInvitation(bool $published = true): Invitation
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'couple@brightdor.test'],
            [
                'name' => 'Couple User',
                'password' => Hash::make('password'),
                'user_type' => 'couple',
                'status' => 'active',
            ],
        );

        $template = InvitationTemplate::query()->firstOrCreate(
            ['slug' => 'elegant-gold'],
            ['name' => 'Elegant Gold', 'price' => 99000, 'is_active' => true],
        );

        $order = InvitationOrder::query()->create([
            'user_id' => $user->id,
            'invitation_template_id' => $template->id,
            'bride_name' => 'Ayu',
            'groom_name' => 'Bima',
            'wedding_date' => now()->addMonths(3)->toDateString(),
            'wedding_venue' => 'Grand Ballroom Jakarta',
            'price' => 99000,
            'status' => 'active',
        ]);

        return Invitation::query()->create([
            'invitation_order_id' => $order->id,
            'user_id' => $user->id,
            'invitation_template_id' => $template->id,
            'slug' => 'bima-ayu-' . ($published ? 'pub' : 'draft'),
            'is_published' => $published,
            'published_at' => $published ? now() : null,
        ]);
    }

    public function test_published_invitation_is_publicly_accessible(): void
    {
        $invitation = $this->makeInvitation();

        $this->get(route('invitations.show', $invitation->slug))
            ->assertOk()
            ->assertSee('Bima')
            ->assertSee('Ayu')
            ->assertSee('Konfirmasi Kehadiran');

        $this->assertSame(1, $invitation->fresh()->views_count);
    }

    public function test_unpublished_invitation_returns_404(): void
    {
        $invitation = $this->makeInvitation(published: false);

        $this->get(route('invitations.show', $invitation->slug))->assertNotFound();
    }

    public function test_guest_can_submit_rsvp_and_counters_update(): void
    {
        $invitation = $this->makeInvitation();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('invitations.rsvp', $invitation->slug), [
                'guest_name' => 'Tamu Undangan',
                'attendance' => 'yes',
                'guest_count' => 2,
                'message' => 'Selamat menempuh hidup baru!',
            ])
            ->assertRedirect()
            ->assertSessionHas('rsvp_success');

        $invitation->refresh();

        $this->assertSame(1, $invitation->rsvp_yes);
        $this->assertSame(0, $invitation->rsvp_no);
        $this->assertDatabaseHas('invitation_rsvps', [
            'invitation_id' => $invitation->id,
            'guest_name' => 'Tamu Undangan',
            'attendance' => 'yes',
            'guest_count' => 2,
        ]);
    }

    public function test_rsvp_validation_rejects_invalid_attendance(): void
    {
        $invitation = $this->makeInvitation();

        $this->withoutMiddleware(VerifyCsrfToken::class)
            ->post(route('invitations.rsvp', $invitation->slug), [
                'guest_name' => 'Tamu',
                'attendance' => 'invalid-value',
            ])
            ->assertSessionHasErrors(['attendance']);
    }
}
