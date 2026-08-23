<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function show(string $slug): View
    {
        $invitation = Invitation::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['order', 'template', 'user'])
            ->firstOrFail();

        $invitation->increment('views_count');

        $rsvps = $invitation->rsvps()
            ->latest()
            ->take(50)
            ->get();

        $content = $invitation->content ?? [];
        $theme = $invitation->theme_settings ?? [];

        return view('frontend.invitations.show', compact(
            'invitation',
            'rsvps',
            'content',
            'theme',
        ));
    }

    public function storeRsvp(Request $request, string $slug): RedirectResponse
    {
        $invitation = Invitation::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'attendance' => ['required', 'in:yes,no,maybe'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($invitation, $validated) {
            $invitation->rsvps()->create([
                'guest_name' => $validated['guest_name'],
                'attendance' => $validated['attendance'],
                'guest_count' => $validated['guest_count'] ?? 1,
                'message' => $validated['message'] ?? null,
            ]);

            $column = match ($validated['attendance']) {
                'yes' => 'rsvp_yes',
                'no' => 'rsvp_no',
                'maybe' => 'rsvp_maybe',
            };

            $invitation->increment($column);
        });

        return back()->with('rsvp_success', 'Terima kasih! Konfirmasi kehadiran kamu sudah tercatat.');
    }
}
