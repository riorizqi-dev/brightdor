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
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->isCouple(), 403, 'Hanya akun user yang dapat didaftarkan sebagai vendor.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        Role::findOrCreate('vendor', 'web');

        $user->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
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
