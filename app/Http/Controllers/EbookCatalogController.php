<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class EbookCatalogController extends Controller
{
    public function access(Request $request, Book $book)
    {
        abort_unless(filled($book->ebook_path), 404);

        if (! $request->user()) {
            $request->session()->put('url.intended', route('reader.show', $book));

            return redirect()->route('register')->with('success', 'Silakan daftar sebagai anggota untuk membaca e-book ini.');
        }

        abort_unless($request->user()->isMember(), 403);

        return redirect()->route('reader.show', $book);
    }

    public function index(Request $request)
    {
        $query = Book::with(['author', 'category'])
            ->whereNotNull('ebook_path')
            ->where('ebook_path', '!=', '');

        if ($request->filled('q')) {
            $term = trim((string) $request->query('q'));
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('author_name', 'like', "%{$term}%")
                    ->orWhereHas('author', fn ($author) => $author->where('name', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $ebooks = $query->orderByDesc('is_featured')->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('ebooks.index', compact('ebooks', 'categories'));
    }
}
