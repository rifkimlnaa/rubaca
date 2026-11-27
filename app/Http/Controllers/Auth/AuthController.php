<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt(
            $credentials + ['is_active' => true],
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors(['username' => 'Username atau kata sandi tidak sesuai.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers(), 'regex:/[A-Z]/'],
            'phone' => ['nullable', 'string', 'max:30'],
            'institution' => ['required', 'string', 'max:180'],
            'occupation' => ['required', 'string', 'max:120'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan.',
            'password' => 'Kata sandi harus minimal 8 karakter, memiliki minimal satu huruf besar dan satu angka.',
            'password.regex' => 'Kata sandi harus memiliki minimal satu huruf besar (A–Z).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $data['role'] = 'member';
        $data['is_active'] = true;
        $data['member_code'] = 'MBR-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Akun anggota berhasil dibuat. Selamat membaca.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
