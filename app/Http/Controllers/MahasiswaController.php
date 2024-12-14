<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MahasiswaController extends Controller
{
    // Menampilkan form tambah mahasiswa
    public function create()
    {
        return view('mahasiswa.tambah-mahasiswa'); 
    }

    // Menyimpan data mahasiswa
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama' => 'required|max:100',
            'npm' => 'required|unique:mahasiswas,npm|max:50',
            'prodi' => 'required|max:100',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Menyimpan file foto jika ada
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/foto_mahasiswa');
        }

        // Menambahkan data mahasiswa ke database
        Mahasiswa::create([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'prodi' => $request->prodi,
            'foto' => $path ? str_replace('public/', '', $path) : null, 
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    // Menampilkan data mahasiswa atau melakukan pencarian
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%")
                  ->orWhere('prodi', 'like', "%{$search}%");
        }

        $mahasiswa = $query->get();

        return view('mahasiswa.mahasiswa', compact('mahasiswa'));
    }

    // Menampilkan form edit mahasiswa
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('mahasiswa.edit-mahasiswa', compact('mahasiswa'));
    }

    // Mengupdate data mahasiswa
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|max:100',
            'npm' => 'required|unique:mahasiswas,npm,' . $id . '|max:50',
            'prodi' => 'required|max:100',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/foto_mahasiswa');
            $mahasiswa->foto = str_replace('public/', '', $path);
        }

        $mahasiswa->update([
            'nama' => $request->nama,
            'npm' => $request->npm,
            'prodi' => $request->prodi,
            'foto' => $mahasiswa->foto,
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    // Menghapus data mahasiswa
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}
