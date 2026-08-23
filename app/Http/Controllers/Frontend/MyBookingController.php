<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MyBookingController extends Controller
{
    public function index(): View
    {
        $bookings = Auth::user()->bookings()
            ->with(['vendor.category', 'service'])
            ->latest()
            ->paginate(10);

        return view('frontend.bookings.index', compact('bookings'));
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        if ((int) $booking->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            return back()->withErrors([
                'booking' => 'Booking dengan status ini tidak dapat dibatalkan.',
            ]);
        }

        try {
            $booking->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->input('cancellation_reason') ?: 'Dibatalkan oleh customer.',
            ])->save();
        } catch (ValidationException $e) {
            return back()->withErrors(['booking' => collect($e->errors())->flatten()->first()]);
        }

        return back()->with('success', 'Booking ' . $booking->booking_code . ' berhasil dibatalkan.');
    }
}
