<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationInvite;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredSupervisorController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $params = ['role' => 'supervisor'];
        if ($request->has('code')) {
            $params['code'] = $request->query('code');
        }
        return redirect()->route('register', $params);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:8'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $invite = RegistrationInvite::where('code', $request->code)->first();

        if (!$invite || !$invite->isValid()) {
            return back()->withErrors(['code' => 'Kode undangan tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        if ($invite->email && $invite->email !== $request->email) {
            return back()->withErrors(['email' => 'Email tidak sesuai dengan kode undangan.'])->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $invite->role,
        ]);

        $invite->markAsUsed($request->email);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
