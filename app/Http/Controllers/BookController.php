<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\{Book,BookCopy,Category};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class BookController extends Controller {
 public function index() { $books=Book::with(['category','copies'])->whereHas('copies')->latest()->paginate(15); return view('books.index',compact('books')); }
 public function create() { return view('books.form',$this->formData(new Book())); }
 public function store(StoreBookRequest $request) {
  $data=$request->validated(); $copies=(int)($data['copies']??0); unset($data['copies']);
  $data['slug']=Str::slug($data['title']).'-'.Str::lower(Str::random(5)); $data['is_featured']=$request->boolean('is_featured');
  if($request->hasFile('cover')) $data['cover_path']=$request->file('cover')->store('covers','public');
  $book=Book::create($data);
  for($i=1;$i<=$copies;$i++) $book->copies()->create(['barcode'=>'RB-'.$book->id.'-'.str_pad((string)$i,4,'0',STR_PAD_LEFT),'status'=>'available']);
  return redirect()->route('books.index')->with('success','Data buku berhasil disimpan.');
 }
 public function edit(Book $book) { return view('books.form',$this->formData($book)); }
 public function update(UpdateBookRequest $request,Book $book) {
  $data=$request->validated(); unset($data['copies']); $data['is_featured']=$request->boolean('is_featured');
  if($request->hasFile('cover')) { if($book->cover_path) Storage::disk('public')->delete($book->cover_path); $data['cover_path']=$request->file('cover')->store('covers','public'); }
  $book->update($data); return redirect()->route('books.index')->with('success','Perubahan buku disimpan.');
 }
 public function addCopies(\Illuminate\Http\Request $request,Book $book) {
  $data=$request->validate(['copies'=>['required','integer','min:1','max:100']]);
  for($i=0;$i<(int)$data['copies'];$i++) $book->copies()->create(['barcode'=>'RB-'.$book->id.'-'.strtoupper(Str::random(7)),'status'=>'available']);
  return back()->with('success',$data['copies'].' eksemplar baru berhasil ditambahkan.');
 }
 public function destroy(Book $book) {
  abort_if($book->loans()->exists(),409,'Buku memiliki riwayat peminjaman dan tidak dapat dihapus. Edit data katalog bila perlu memperbarui informasinya.');
  if($book->ebook_path) Storage::disk('private')->delete($book->ebook_path); if($book->cover_path) Storage::disk('public')->delete($book->cover_path); $book->delete();
  return back()->with('success','Buku berhasil dihapus.');
 }
 private function formData(Book $book): array { return ['book'=>$book,'categories'=>Category::orderBy('name')->get()]; }
}