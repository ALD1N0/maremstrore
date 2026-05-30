<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Barang::all();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('gambar')->store('produk', 'public');

        Barang::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $path
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }


    public function edit(Barang $produk)
    {
        return view('produk.edit', compact('produk'));
    }


    public function update(Request $request, Barang $produk)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('gambar')) {

            if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
                Storage::delete('public/' . $produk->gambar);
            }

            $path = $request->file('gambar')->store('produk', 'public');
            $produk->gambar = $path;
        }

        $produk->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $produk->gambar
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate');
    }


    public function destroy(Barang $produk)
    {
        if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
            Storage::delete('public/' . $produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
