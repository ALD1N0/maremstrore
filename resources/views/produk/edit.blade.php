@extends('layouts.app')
@section('content')
    <style>
        .form-page {
            min-height: 100vh;
            padding: 40px 20px;
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 100%;
            max-width: 550px;
            background: #ffffff;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }
        .form-container h2 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group p {
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            outline: none;
            font-size: 14px;
            transition: .2s;
        }
        .form-group input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
        }
        .preview-img {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
        }
        .no-img {
            padding: 20px;
            background: #f3f4f6;
            border-radius: 12px;
            color: #6b7280;
            text-align: center;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }
        .btn-primary {
            background: #16a34a;
            color: white;
        }
        .btn-primary:hover {
            background: #15803d;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .btn-loading {
            opacity: .7;
            pointer-events: none;
        }
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(3px);
        }
        .loading-box {
            background: white;
            padding: 25px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #e5e7eb;
            border-top: 5px solid #16a34a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
            margin-bottom: 15px;
        }
        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-box">
            <div class="spinner"></div>
            <p>Mengupdate produk...</p>
        </div>
    </div>
    <div class="form-container form-page">
        <div class="form-container">
            <h2>Edit Produk</h2>
            {{-- ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form id="formProduk" action="{{ route('produk.update', $produk) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <p>Nama</p>
                    <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required>
                </div>
                <div class="form-group">
                    <p>Harga</p>
                    <input type="number" name="harga" min="0" value="{{ old('harga', $produk->harga) }}" required>
                </div>
                <div class="form-group">
                    <p>Stok</p>
                    <input type="number" name="stok" min="0" value="{{ old('stok', $produk->stok) }}" required>
                </div>
                <div class="form-group">
                    <p>Gambar Saat Ini</p>
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" class="preview-img" id="previewGambar">
                    @else
                        <p class="no-img">
                            Tidak ada gambar
                        </p>
                        <img id="previewGambar" class="preview-img" style="display:none;">
                    @endif
                </div>
                <div class="form-group">
                    <p>Ganti Gambar</p>
                    <input type="file" name="gambar" id="inputGambar" accept="image/*">
                </div>
                <div class="form-actions">
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        Update
                    </button>
                    <a href="{{ route('produk.index') }}" style="flex:1;">
                        <button type="button" class="btn btn-secondary" style="width:100%;">
                            Kembali
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script>
        const inputGambar = document.getElementById('inputGambar');
        const previewGambar = document.getElementById('previewGambar');
        inputGambar.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                previewGambar.src = URL.createObjectURL(file);
                previewGambar.style.display = 'block';
            }
        });
        const form = document.getElementById('formProduk');
        const loading = document.getElementById('loadingOverlay');
        const btnSubmit = document.getElementById('btnSubmit');
        form.addEventListener('submit', function () {
            loading.style.display = 'flex';
            btnSubmit.innerHTML = 'Mengupdate...';
            btnSubmit.disabled = true;
            btnSubmit.classList.add('btn-loading');
        });
    </script>
@endsection
