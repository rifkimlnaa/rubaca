<?php
namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class EbookAdminController extends Controller {
 public function index(){ $ebooks=Book::with('category')->whereNotNull('ebook_path')->where('ebook_path','!=','')->latest()->paginate(15); return view('admin.ebooks.index',compact('ebooks')); }
 public function create(){ $book=new Book(); $categories=Category::orderBy('name')->get(); return view('admin.ebooks.form',compact('book','categories')); }
 public function store(Request $request){ $data=$this->validateData($request); $data['slug']=Str::slug($data['title']).'-'.Str::lower(Str::random(5)); $data['is_featured']=$request->boolean('is_featured'); $data['ebook_path']=$request->file('ebook')->store('ebooks','private'); $data['ebook_mime']='application/pdf'; if($request->hasFile('cover')) $data['cover_path']=$request->file('cover')->store('covers','public'); $book=Book::create($data); return redirect()->route('admin.ebooks.index')->with('success','E-book berhasil ditambahkan.'); }
 public function edit(Book $book){ abort_unless($book->ebook_path,404); $categories=Category::orderBy('name')->get(); return view('admin.ebooks.form',compact('book','categories')); }
 public function update(Request $request,Book $book){ abort_unless($book->ebook_path,404); $data=$this->validateData($request,false); $data['is_featured']=$request->boolean('is_featured'); if($request->hasFile('ebook')){ Storage::disk('private')->delete($book->ebook_path); $data['ebook_path']=$request->file('ebook')->store('ebooks','private'); $data['ebook_mime']='application/pdf'; } if($request->hasFile('cover')){ if($book->cover_path) Storage::disk('public')->delete($book->cover_path); $data['cover_path']=$request->file('cover')->store('covers','public'); } $book->update($data); return redirect()->route('admin.ebooks.index')->with('success','E-book berhasil diperbarui.'); }
 private function validateData(Request $request,bool $requireFile=true):array { return $request->validate(['title'=>['required','string','max:180'],'category_id'=>['required','exists:categories,id'],'author_name'=>['nullable','string','max:180'],'publisher_name'=>['nullable','string','max:180'],'shelf_label'=>['nullable','string','max:120'],'isbn'=>['nullable','string','max:30'],'published_year'=>['nullable','integer','min:1400','max:'.(date('Y')+1)],'description'=>['nullable','string','max:5000'],'is_featured'=>['sometimes','boolean'],'ebook'=>[$requireFile?'required':'nullable','file','mimes:pdf','max:51200'],'cover'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:3072']]); }
}
