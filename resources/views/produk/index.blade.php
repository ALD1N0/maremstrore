@extends('layouts.app')
@section('content')
    <div class="produk-page">
        <div class="produk-toolbar">
            <div class="produk-search-wrap">
                <input type="text" id="searchProduk" class="search produk-search" placeholder="Cari barang..."
                    onkeyup="cariProdukTabel()">
                <select onchange="filterbarangproduk()" id="filterbarangproduk" class="produk-filter">
                    <option value="">-- Urutkan --</option>
                    <option value="nama">Nama A-Z</option>
                    <option value="stok">Stok Terendah</option>
                </select>
            </div>
            <div class="produk-header">
                <h2 class="produk-title">
                    Data Produk
                </h2>
                <div class="produk-actions">
                    <button onclick="printStokHabis()" class="btn-print-stock">
                        🖨 Cetak Stok Menipis 
                    </button>
                    <a href="{{ route('produk.create') }}">
                        <button class="btn-add-product">
                            + Tambah Barang
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="table-wrapper">
            <table border="1" cellpadding="10" id="tabelProduk" class="produk-table">
                <tr>
                    <th>Nama</th>
                    <th>Foto</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                @foreach($produks as $p)
                    <tr>
                        <td class="nama-produk">
                            {{ $p->nama }}
                        </td>
                        <td>
                            @if($p->gambar)
                                <img src="{{ asset('storage/' . $p->gambar) }}" class="produk-image">
                            @else
                                <span class="no-image">
                                    Tidak ada
                                </span>
                            @endif
                        </td>
                        <td class="harga-cell">
                            Rp {{ number_format($p->harga) }}
                        </td>
                        <td class="stok-cell">
                            {{ $p->stok }}
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('produk.edit', $p) }}">
                                    <button class="btn-edit">
                                        Edit
                                    </button>
                                </a>
                                <form action="{{ route('produk.destroy', $p) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirmDelete(this)">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
