<?php
namespace App\Http\Controllers;
use App\Models\{Book,BookCopy,LibrarySetting,Loan};
use App\Notifications\LoanStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LoanController extends Controller {
 public function mine(Request $request) { $loans=$request->user()->loans()->with(['book','copy'])->latest()->paginate(15); return view('loans.index',compact('loans')); }
 public function staffIndex(Request $request) { $query=Loan::with(['book','user','copy'])->latest(); if($request->filled('status')) $query->where('status',$request->status); $loans=$query->paginate(20)->withQueryString(); return view('loans.index',compact('loans')); }
 public function requestBorrow(Request $request,Book $book) {
  $this->authorize('request',Loan::class);
  abort_if($book->copies()->where('status','available')->count()<1,409,'Tidak ada eksemplar yang tersedia saat ini.');
  $limit=(int)LibrarySetting::get('max_active_loans',3); $active=$request->user()->loans()->whereIn('status',['requested','borrowed'])->count();
  abort_if($active >= $limit,409,'Batas pinjaman aktif Anda telah tercapai.');
  $exists=$request->user()->loans()->where('book_id',$book->id)->whereIn('status',['requested','borrowed'])->exists(); abort_if($exists,409,'Anda sudah memiliki permintaan atau pinjaman untuk buku ini.');
  $loan=Loan::create(['user_id'=>$request->user()->id,'book_id'=>$book->id,'status'=>'requested','requested_at'=>now(),'fine_amount'=>0]);
  return back()->with('success','Permintaan peminjaman dikirim dan menunggu verifikasi petugas.');
 }
 public function approve(Loan $loan) {
  DB::transaction(function() use($loan) {
   $loan=Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail(); abort_unless($loan->status==='requested',409,'Permintaan sudah diproses.');
   $copy=BookCopy::where('book_id',$loan->book_id)->where('status','available')->lockForUpdate()->first(); abort_if(!$copy,409,'Stok sudah tidak tersedia.');
   $copy->update(['status'=>'loaned']); $days=(int)LibrarySetting::get('loan_days',5);
   $loan->update(['book_copy_id'=>$copy->id,'status'=>'borrowed','borrowed_at'=>now(),'due_at'=>now()->addDays($days)]);
  });
  $loan->refresh(); $loan->user->notify(new LoanStatusNotification('Peminjaman disetujui','Buku '.$loan->book->title.' dapat diambil. Tenggat: '.$loan->due_at->format('d/m/Y'),'/loans/mine'));
  return back()->with('success','Peminjaman disetujui dan eksemplar dialokasikan.');
 }
 public function reject(Request $request,Loan $loan) {
  abort_unless($loan->status==='requested',409,'Permintaan sudah diproses.'); $data=$request->validate(['staff_note'=>['nullable','string','max:500']]); $loan->update(['status'=>'rejected','staff_note'=>$data['staff_note']??null]);
  $loan->user->notify(new LoanStatusNotification('Peminjaman ditolak',$data['staff_note']??'Permintaan peminjaman belum dapat disetujui.','/loans/mine'));
  return back()->with('success','Permintaan ditolak.');
 }
 public function returnBook(Loan $loan) {
  DB::transaction(function() use($loan) {
   $loan=Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail(); abort_unless($loan->status==='borrowed',409,'Pinjaman tidak sedang aktif.');
   if($loan->book_copy_id) BookCopy::whereKey($loan->book_copy_id)->lockForUpdate()->first()?->update(['status'=>'available']);
   $overdueDays=max(0,(int)($loan->due_at?->startOfDay()->diffInDays(now()->startOfDay(),false) ?? 0)); $fineRate=(float)LibrarySetting::get('fine_per_day',1000);
   $loan->update(['status'=>'returned','returned_at'=>now(),'fine_amount'=>$overdueDays*$fineRate]);
  });
  $loan->refresh()->user->notify(new LoanStatusNotification('Buku telah dikembalikan','Pengembalian '.$loan->book->title.' tercatat. Denda: Rp '.number_format((float)$loan->fine_amount,0,',','.').'.','/loans/mine'));
  return back()->with('success','Pengembalian berhasil diproses.');
 }
 public function verifyFine(Request $request,Loan $loan) {
  abort_unless($loan->status==='returned',409,'Denda hanya dapat diverifikasi setelah buku dikembalikan.');
  abort_if((float)$loan->fine_amount<=0,409,'Tidak ada denda untuk diverifikasi.');
  abort_if($loan->fine_verified_at!==null,409,'Denda sudah diverifikasi.');
  $loan->update(['fine_verified_at'=>now(),'fine_verified_by'=>$request->user()->id]);
  $loan->user->notify(new LoanStatusNotification('Denda diverifikasi','Denda pengembalian '.$loan->book->title.' telah diverifikasi petugas sebesar Rp '.number_format((float)$loan->fine_amount,0,',','.').'.','/loans/mine'));
  return back()->with('success','Denda berhasil diverifikasi.');
 }
 public function requestRenewal(Request $request,Loan $loan) {
  abort_unless($loan->user_id===$request->user()->id && $loan->status==='borrowed',403); abort_if($loan->renewals>=2,409,'Batas perpanjangan sudah tercapai.');
  $loan->update(['renewal_requested_at'=>now()]); return back()->with('success','Permintaan perpanjangan dikirim.');
 }
 public function reviewRenewal(Request $request,Loan $loan) {
  abort_unless($loan->renewal_requested_at && $loan->status==='borrowed',409,'Tidak ada perpanjangan yang menunggu.');
  $data=$request->validate(['decision'=>['required','in:approve,reject']]);
  if($data['decision']==='approve') $loan->update(['due_at'=>$loan->due_at->addDays((int)LibrarySetting::get('renewal_days',7)),'renewals'=>$loan->renewals+1,'renewal_requested_at'=>null]);
  else $loan->update(['renewal_requested_at'=>null]);
  $loan->user->notify(new LoanStatusNotification('Status perpanjangan',$data['decision']==='approve'?'Perpanjangan disetujui. Tenggat baru: '.$loan->fresh()->due_at->format('d/m/Y'):'Perpanjangan belum disetujui.','/loans/mine'));
  return back()->with('success','Permintaan perpanjangan telah diproses.');
 }
}