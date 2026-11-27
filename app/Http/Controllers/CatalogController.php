<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
class CatalogController extends Controller {
 public function index(Request $request) {
  $query=Book::with(['author','category','copies']);
  if($request->filled('q')) $query->where(fn($q)=>$q->where('title','like','%'.$request->q.'%')->orWhere('isbn','like','%'.$request->q.'%')->orWhereHas('author',fn($a)=>$a->where('name','like','%'.$request->q.'%')));
  if($request->filled('category')) $query->where('category_id',$request->integer('category'));
  if($request->input('format')==='ebook') $query->whereNotNull('ebook_path')->where('ebook_path','!=','');
  if($request->input('format')==='fisik') $query->whereHas('copies');
  $books=$query->latest()->paginate(12)->withQueryString();
  $categories=Category::orderBy('name')->get();
  return view('catalog.index',compact('books','categories'));
 }
 public function search(Request $request) {
  $q=trim((string)$request->query('q',''));
  $books=Book::with(['author','category','copies'])->when($q!=='',fn($query)=>$query->where(fn($nested)=>$nested->where('title','like',"%{$q}%")->orWhereHas('author',fn($a)=>$a->where('name','like',"%{$q}%"))))->when($request->filled('category'),fn($query)=>$query->where('category_id',$request->integer('category')))->latest()->limit(8)->get();
  return response()->json($books->map(fn($b)=>['id'=>$b->id,'title'=>$b->title,'author'=>$b->author?->name ?? 'Penulis tidak dicantumkan','category'=>$b->category?->name,'available'=>$b->available_count,'ebook'=>(bool)$b->ebook_path,'url'=>route('catalog.show',$b)]));
 }
 public function show(Book $book) { $book->load(['author','publisher','category','shelf','copies']); return view('catalog.show',compact('book')); }
}