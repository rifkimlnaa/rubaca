<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller {
 public function showLogin() { return view('auth.login'); }
 public function login(Request $request) {
  $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
  if (!Auth::attempt($credentials + ['is_active'=>true],$request->boolean('remember'))) return back()->withErrors(['email'=>'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
  $request->session()->regenerate();
  return redirect()->intended(route('dashboard'))->with('success','Selamat datang kembali.');
 }
 public function showRegister() { return view('auth.register'); }
 public function register(Request $request) {
  $data=$request->validate(
   ['name'=>['required','string','max:120'],
   'email'=>['required','email','max:180','unique:users,email'],
   'password'=>['required','confirmed',Password::min(8)->numbers(),'regex:/[A-Z]/'],
   'phone'=>['nullable','string','max:30'],
   'institution'=>['required','string','max:180'],
   'occupation'=>['required','string','max:120']],
   ['password'=>'Kata sandi harus minimal 8 karakter, memiliki minimal satu huruf besar dan satu angka.','password.regex'=>'Kata sandi harus memiliki minimal satu huruf besar (A–Z).','password.confirmed'=>'Konfirmasi kata sandi tidak cocok.']
  );
  $data['role']='member'; $data['is_active']=true; $data['member_code']='MBR-'.strtoupper(substr(bin2hex(random_bytes(5)),0,8));
  $user=User::create($data); Auth::login($user); $request->session()->regenerate();
  return redirect()->intended(route('dashboard'))->with('success','Akun anggota berhasil dibuat. Selamat membaca.');
 }
 public function logout(Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('home'); }
}