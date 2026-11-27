<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
class UserController extends Controller {
 public function index() { $users=User::latest()->paginate(20); return view('users.index',compact('users')); }
 public function store(Request $request) {
  $data=$request->validate(['name'=>['required','string','max:120'],'username'=>['required','string','min:3','max:50','alpha_dash','unique:users,username'],'email'=>['required','email','unique:users,email'],'role'=>['required','in:admin,staff,member'],'password'=>['required',Password::min(10)->letters()->numbers()],'phone'=>['nullable','string','max:30'],'institution'=>['nullable','string','max:180'],'occupation'=>['nullable','string','max:120']]);
  $data['member_code']=$data['role']==='member'?'MBR-'.strtoupper(Str::random(8)):null; $data['is_active']=true; User::create($data); return back()->with('success','Akun berhasil dibuat.');
 }
 public function update(Request $request,User $user) {
  abort_if($user->id===$request->user()->id,403,'Anda tidak dapat mengubah role akun sendiri.'); $data=$request->validate(['role'=>['required','in:admin,staff,member'],'is_active'=>['required','boolean']]); $user->update($data); return back()->with('success','Akun diperbarui.');
 }
}