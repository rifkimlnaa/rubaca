<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:180'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->numbers(),
                'regex:/[A-Z]/',
            ],
        ], [
            'password' => 'Kata sandi harus minimal 8 karakter, memiliki minimal satu huruf besar dan satu angka.',
            'password.regex' => 'Kata sandi harus memiliki minimal satu huruf besar (A–Z).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with(
                'success',
                'Kata sandi berhasil diperbarui. Silakan masuk menggunakan kata sandi baru.'
            );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Tautan pemulihan tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.']);
    }
}
