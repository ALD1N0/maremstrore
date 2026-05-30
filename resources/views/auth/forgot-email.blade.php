<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Marem Store')</title>
    <link rel="stylesheet" href="{{ asset("css/lupasandi.css") }}">
</head>
<body class="forgot-page">
    <div class="forgot-wrapper">
        <!-- CARD -->
        <div class="forgot-card">
            <!-- HEADER -->
            <div class="forgot-header">
                <h2 class="forgot-title">
                    Lupa Password
                </h2>
                <p class="forgot-subtitle">
                    Masukkan email untuk menerima kode OTP
                </p>
            </div>
            <!-- ERROR SESSION -->
            @if(session('error'))
                <div class="error-box">
                    {{ session('error') }}
                </div>
            @endif
            <!-- VALIDATION -->
            @if($errors->any())
                <ul class="validation-box">
                    @foreach($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            @endif
            <!-- FORM -->
            <form method="POST" action="{{ route('forgot.send') }}" class="forgot-form">
                @csrf
                <input type="email" name="email" placeholder="Masukkan Email" class="forgot-input" required>
                <button type="submit" class="forgot-button">
                    Kirim OTP
                </button>
            </form>
        </div>
    </div>
</body>
</html>
