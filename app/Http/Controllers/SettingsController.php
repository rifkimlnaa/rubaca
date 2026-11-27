<?php
namespace App\Http\Controllers;
use App\Models\LibrarySetting;
use Illuminate\Http\Request;
class SettingsController extends Controller {
 public function update(Request $request) {
  $data=$request->validate(['loan_days'=>['required','integer','min:1','max:90'],'renewal_days'=>['required','integer','min:1','max:60'],'fine_per_day'=>['required','integer','min:0','max:100000'],'max_active_loans'=>['required','integer','min:1','max:20']]);
  foreach($data as $key=>$value) LibrarySetting::put($key,$value);
  return back()->with('success','Aturan peminjaman diperbarui.');
 }
}
