@extends('layouts.app')
@section('content')
<div class="profile-page">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h2>{{ Auth::user()->name }}</h2>
            <p>Admin</p>
        </div>
        @if(session('success'))
            <div class="profile-alert profile-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="profile-alert profile-error">
                {{ session('error') }}
            </div>
        @endif
        <div class="profile-info-box">
            <h3>Informasi Akun</h3>
            <div class="profile-info-item">
                <span>Nama</span>
                <strong>{{ Auth::user()->name }}</strong>
            </div>
            <div class="profile-info-item">
                <span>Email</span>
                <strong>{{ Auth::user()->email }}</strong>
            </div>
        </div>
        <div class="profile-form-box">
            <h3>Keamanan Akun</h3>
            <a href="{{ route('profile.email.form') }}" class="profile-btn-link">
                Ganti Email
            </a>
            <a href="{{ route('profile.password.form') }}" class="profile-btn-link">
                Ganti Password
            </a>
            <form method="POST" action="/logout" class="logout-form">
                @csrf
                <button
                    type="submit"
                    class="btn-logout"
                    onclick="return confirmLogout(this)"
                    style="color:red;">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection
