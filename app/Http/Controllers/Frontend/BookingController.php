<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $vendor = Vendor::query()
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'service_id' => ['required', 'integer'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'event_location' => ['nullable', 'string', 'max:255'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $service = $vendor->services()
            ->where('status', 'published')
            ->where('is_active', true)
            ->findOrFail($validated['service_id']);

        $user = Auth::user();

        if ($user && ! $user->isCouple()) {
            abort(403);
        }

        if ($user) {
            $validated['name'] = $user->name;
            $validated['email'] = $user->email;
            $validated['phone'] = $user->phone ?? $validated['phone'];
        }

        $existingUser = User::query()->where('email', $validated['email'])->first();

        if ($existingUser && ($existingUser->status !== 'active' || ! $existingUser->isCouple())) {
            abort(422, 'Email tersebut tidak dapat digunakan untuk booking.');
        }

        if ($user && $user->id !== $existingUser?->id) {
            abort(403);
        }

        if ($existingUser && !$user) {
            return back()->withErrors([
                'email' => 'Email ini sudah terdaftar. Silakan login terlebih dahulu untuk membuat booking.',
            ])->withInput();
        }

        $user = $existingUser ?? User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'user_type' => 'couple',
            'status' => 'active',
            'password' => Hash::make(Str::random(32)),
        ]);

        $user->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ])->save();

        $subtotal = $service->final_price;

        $booking = Booking::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'service_id' => $service->id,
            'event_date' => $validated['event_date'] ?? null,
            'event_time' => $validated['event_time'] ?? null,
            'event_location' => $validated['event_location'] ?? null,
            'guest_count' => $validated['guest_count'] ?? null,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'subtotal' => $subtotal,
            'discount' => 0,
            'admin_fee' => 0,
            'commission_amount' => 0,
            'total_amount' => $subtotal,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Permintaan booking terkirim dengan kode ' . $booking->booking_code . '. Tim BrightDor akan segera menghubungi kamu untuk konfirmasi.',
        );
    }
}