<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.passwords.email');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:180'],
        ]);

        // Always use the same public response to avoid revealing whether an
        // address belongs to an account. Laravel's broker enforces throttling.
        Password::sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'Jika alamat email terdaftar, tautan pemulihan akan segera dikirim. Periksa kotak masuk atau folder spam.'
        );
    }
}
