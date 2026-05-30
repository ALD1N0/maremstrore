<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', 'Marem Store')</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2 class="login-title">
                    MAREM STORE
                </h2>
                <p class="login-subtitle">
                    Login ke dashboard
                </p>
            </div>

            @if ($errors->any())
                <div class="login-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="login-error">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="/login" class="login-form">
                @csrf
                <input type="email" name="email" placeholder="Email" class="login-input">
                <div class="password-group">
                    <input type="password" name="password" id="password" placeholder="Password" class="login-input">
                    <i class="bi bi-eye toggle-password" id="eyeIcon" onclick="togglePassword()"></i>
                </div>
                <button type="submit" class="login-button">
                    Login
                </button>
            </form>
            <a href="{{ route('forgot.form') }}" class="forgot-link">
                Lupa sandi?
            </a>
        </div>
    </div>
    <script>
        function togglePassword() {
            const password =
                document.getElementById(
                    "password"
                );
            if (
                password.type === "password"
            ) {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</body>

</html>
