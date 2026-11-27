<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterDataController extends Controller
{
    private function validateType(string $type): void
    {
        abort_unless($type === 'categories', 404);
    }

    public function index(string $type)
    {
        $this->validateType($type);
        $items = Category::orderBy('name')->paginate(20);
        return view('master.index', compact('items', 'type'));
    }

    public function store(Request $request, string $type)
    {
        $this->validateType($type);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori buku berhasil ditambahkan.');
    }


    public function destroy(string $type, int $id)
    {
        $this->validateType($type);
        $item = Category::findOrFail($id);
        abort_if($item->books()->exists(), 409, 'Kategori masih digunakan buku.');
        $item->delete();
        return back()->with('success', 'Kategori buku berhasil dihapus.');
    }
}
