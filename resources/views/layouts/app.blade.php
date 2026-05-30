<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Marem Store')</title>

    <link rel="stylesheet" href="{{ asset('css/barang.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form_edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/struk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body>

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/form.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
