<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorRegisterController extends Controller
{
    public function create(): View
    {
        return view('frontend.register.vendor');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            abort_unless(Auth::user()->isCouple(), 403, 'Hanya akun couple yang dapat didaftarkan sebagai vendor.');

            return $this->upgrade($request);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'user_type' => 'vendor',
            'status' => 'active',
            'password' => $validated['password'],
        ]);

        $user->assignRole('vendor');

        return redirect()->route('vendors.register.create')
            ->with('success', 'Pendaftaran berhasil! Tim BrightDor akan menghubungi Anda untuk verifikasi akun.');
    }

    protected function upgrade(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'user_type' => 'vendor',
        ])->save();

        if (! $user->hasRole('vendor')) {
            $user->assignRole('vendor');
        }

        return redirect('/vendor')
            ->with('status', 'Akun Anda kini terdaftar sebagai vendor. Lengkapi profil vendor untuk tampil di marketplace.');
    }
}
