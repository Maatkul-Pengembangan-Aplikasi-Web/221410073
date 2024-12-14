<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    // Menampilkan daftar Program Studi
    public function index(Request $request)
    {
        $search = $request->input('search');
        $prodis = Prodi::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('prodi.index', compact('prodis', 'search'));
    }

    // Menampilkan formulir untuk menambahkan Program Studi
    public function create()
    {
        return view('prodi.create');
    }

    // Menyimpan Program Studi baru
    public function save(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255'
        ]);

        Prodi::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('prodi.index')->with('success', 'Program Studi berhasil ditambahkan');
    }

    // Menampilkan formulir untuk mengedit Program Studi
    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.edit', compact('prodi'));
    }

    // Memperbarui data Program Studi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255'
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('prodi.index')->with('success', 'Program Studi berhasil diperbarui');
    }

    // Menghapus data Program Studi
    public function delete($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Data Program Studi berhasil dihapus');
    }
}
