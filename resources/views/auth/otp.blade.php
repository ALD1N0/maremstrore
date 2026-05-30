<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
   <title>@yield('title', 'Marem Store')</title>
<link rel="stylesheet" href="{{ asset("css/otp.css") }}">
</head>
<body class="otp-page">

    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>

    <div class="otp-wrapper">
        <div class="otp-card">
            <div class="otp-header">
                <h2 class="otp-title">
                    Verifikasi OTP
                </h2>
                <p class="otp-subtitle">
                    Masukkan kode OTP yang dikirim
                </p>
            </div>

            @error('otp')
                <div class="otp-error">
                    {{ $message }}
                </div>
            @enderror

            <form
                method="POST"
                action="{{ route('otp.verify') }}"
                class="otp-form">
                @csrf
                <input
                    type="text"
                    name="otp"
                    value="{{ old('otp') }}"
                    placeholder="000000"
                    class="otp-input">
                <button
                    type="submit"
                    class="otp-button">
                    Verifikasi
                </button>
            </form>
        </div>
    </div>
</body>
</html>
