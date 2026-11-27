<?php
namespace App\Http\Controllers;
use App\Models\{Book,Loan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller {
 public function index(Request $request) {
  $from=$request->date('from') ?? now()->startOfMonth(); $to=$request->date('to') ?? now()->endOfMonth();
  $loans=Loan::with(['book','user'])->whereBetween('requested_at',[$from->startOfDay(),$to->endOfDay()])->latest()->paginate(25)->withQueryString();
  $summary=['Permintaan'=>$loans->total(),'Disetujui'=>Loan::where('status','borrowed')->whereBetween('borrowed_at',[$from,$to])->count(),'Dikembalikan'=>Loan::where('status','returned')->whereBetween('returned_at',[$from,$to])->count(),'Total denda'=>Loan::whereBetween('returned_at',[$from,$to])->sum('fine_amount')];
  $popular=Loan::select('book_id',DB::raw('count(*) as total'))->whereBetween('requested_at',[$from,$to])->groupBy('book_id')->orderByDesc('total')->with('book')->take(5)->get();
  return view('reports.index',compact('loans','summary','popular','from','to'));
 }
}