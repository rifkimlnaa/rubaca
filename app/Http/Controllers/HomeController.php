<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\User;
class HomeController extends Controller {
 public function index() {
  $stats=['titles'=>Book::count(),'copies'=>BookCopy::where('status','available')->count(),'members'=>User::where('role','member')->count(),'ebooks'=>Book::whereNotNull('ebook_path')->count()];
  $featured=Book::with(['author','category','copies'])->whereHas('copies')->where('is_featured',true)->latest()->take(4)->get();
  if($featured->isEmpty()) $featured=Book::with(['author','category','copies'])->whereHas('copies')->latest()->take(4)->get();
  $featuredEbooks=Book::with(['author','category'])->whereNotNull('ebook_path')->where('ebook_path','!=','')->where('is_featured',true)->latest()->take(3)->get();
  if($featuredEbooks->isEmpty()) $featuredEbooks=Book::with(['author','category'])->whereNotNull('ebook_path')->where('ebook_path','!=','')->latest()->take(3)->get();
  $categories=Category::withCount('books')->orderBy('name')->get();
  return view('welcome',compact('stats','featured','featuredEbooks','categories'));
 }
}