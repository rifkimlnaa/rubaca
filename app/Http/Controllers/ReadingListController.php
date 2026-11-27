<?php
namespace App\Http\Controllers;
use App\Models\{Book,ReadingList};
use Illuminate\Http\Request;
class ReadingListController extends Controller {
 public function index(Request $request) { $books=$request->user()->readingLists()->with('book.author')->latest()->paginate(12); return view('reading-list.index',compact('books')); }
 public function toggle(Request $request,Book $book) {
  $item=ReadingList::where('user_id',$request->user()->id)->where('book_id',$book->id)->first();
  if($item) { $item->delete(); $message='Buku dihapus dari daftar bacaan.'; } else { ReadingList::create(['user_id'=>$request->user()->id,'book_id'=>$book->id]); $message='Buku disimpan ke daftar bacaan.'; }
  return back()->with('success',$message);
 }
}