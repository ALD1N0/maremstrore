<section class="produk">
    <div class="produk-top">
        <input type="text" id="searchInput" class="search" placeholder="Cari barang..." onkeyup="cariProduk()">
        <select onchange="filterProduk()" id="filterProduk" class="filter">
            <option value="">Urutkan</option>
            <option value="nama">Nama A-Z</option>
            <option value="stok">Stok Terendah</option>
        </select>
    </div>

    <div class="grid" id="produkGrid">

        @foreach($produks as $produk)
            <div class="card {{ $produk->stok == 0 ? 'habis' : '' }}"
                onclick="tambahBarang(null, {{ $produk->id_barang }}, '{{ addslashes($produk->nama) }}', {{ $produk->harga }}, {{ $produk->stok }}, this)" >

                <div class="img-wrapper">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}">
                    @else
                        <div class="img-placeholder">No Image</div>
                    @endif
                </div>

                <div class="card-body">
                    <p class="nama">{{ $produk->nama }}</p>
                    <p class="harga">Rp {{ number_format($produk->harga) }}</p>
                    <p class="stok" data-stok="{{ $produk->stok }}">
                        Stok: {{ $produk->stok }}
                    </p>

                    <button class="btn-add" {{ $produk->stok == 0 ? 'disabled' : '' }}
                        onclick="event.stopPropagation(); tambahBarang(this, {{ $produk->id_barang }}, '{{ addslashes($produk->nama) }}', {{ $produk->harga }}, {{ $produk->stok }})">

                        {{ $produk->stok == 0 ? 'Habis' : ' Tambah' }}
                    </button>
                </div>

            </div>

        @endforeach
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
