<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(Booking $booking): View
    {
        if ((int) $booking->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            abort(403, 'Review hanya bisa diberikan setelah acara selesai.');
        }

        if ($booking->review) {
            return redirect()->route('my-bookings.index')
                ->with('error', 'Anda sudah memberikan review untuk booking ini.');
        }

        $booking->load(['vendor', 'service']);

        return view('frontend.reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        if ((int) $booking->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->withErrors(['booking' => 'Review hanya bisa diberikan setelah acara selesai.']);
        }

        if ($booking->review) {
            return back()->withErrors(['booking' => 'Anda sudah memberikan review untuk booking ini.']);
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            Review::create([
                'vendor_id' => $booking->vendor_id,
                'service_id' => $booking->service_id,
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'rating' => $validated['rating'],
                'content' => $validated['content'] ?? null,
                'is_verified' => true,
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors(['rating' => collect($e->errors())->flatten()->first()]);
        }

        return redirect()->route('my-bookings.index')
            ->with('success', 'Terima kasih! Review Anda telah disimpan.');
    }
}