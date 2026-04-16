<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function tampil()
    {
        $data = Category::all();
        return view('category.tampil', compact('data'));
    }

    public function tambah()
    {
        return view('category.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string',
        ]);

        $category = Category::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // ← LOG
        ActivityLog::log('create', 'category', "Menambah kategori: {$category->name}");

        return redirect()->route('category.tampil')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'required|string',
        ]);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // ← LOG
        ActivityLog::log('update', 'category', "Mengubah kategori: {$category->name}");

        return redirect()->route('category.tampil')->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // ← LOG (sebelum delete)
        ActivityLog::log('delete', 'category', "Menghapus kategori: {$category->name}");

        $category->delete();
        return redirect()->route('category.tampil')->with('success', 'Kategori berhasil dihapus!');
    }
}
