<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class VendorRegisterController extends Controller
{
    public function create(): View
    {
        abort_unless(Auth::user()->isCouple(), 403, 'Hanya akun user yang dapat didaftarkan sebagai vendor.');

        return view('frontend.register.vendor');
    }

    public function store(Request $request): RedirectResponse
    {
        // Route group uses `auth` middleware; unauthenticated requests are redirected to login before reaching here.
        // Guard against admin misuse; normal couples are handled via upgrade().
        if (Auth::user()->isAdmin() || Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Admin tidak dapat mendaftarkan akun sebagai vendor.');
        }

        return $this->upgrade($request);
    }

    protected function upgrade(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->isCouple(), 403, 'Hanya akun user yang dapat didaftarkan sebagai vendor.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'subscription_plan' => ['nullable', 'string'],
        ]);

        // Expired subscribers get a distinct message so they know to renew
        // instead of being told they never subscribed at all.
        abort_if(
            $user->hasExpiredVendorSubscription(),
            403,
            'Langganan paket vendor Anda sudah habis. Perpanjang dulu untuk melanjutkan pendaftaran vendor.',
        );

        abort_if(
            ! $user->hasPaidVendorSubscription(),
            403,
            'Untuk menjadi vendor, Anda harus aktif berlangganan paket vendor berbayar.',
        );

        Role::findOrCreate('vendor', 'web');

        $user->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'vendor_subscription_status' => 'active',
            'vendor_subscription_plan' => $validated['subscription_plan'] ?? $user->vendor_subscription_plan ?? 'premium_monthly',
            'user_type' => 'vendor',
        ])->save();

        Vendor::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $validated['name'],
                'phone' => $validated['phone'],
                'whatsapp' => $validated['phone'],
                'status' => 'pending',
                'is_verified' => false,
            ],
        );

        if (! $user->hasRole('vendor')) {
            $user->assignRole('vendor');
        }

        return redirect('/vendor')
            ->with('status', 'Akun Anda kini terdaftar sebagai vendor. Lengkapi profil vendor untuk tampil di marketplace.');
    }
}