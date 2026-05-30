@extends('layouts.app')
@section('content')
<style>
    .profile-page{
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        font-family: Arial, sans-serif;
        /* FIX */
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .profile-page h2{
        margin-bottom: 20px;
        color: #1f2937;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
    }
    .profile-page div{
        margin-bottom: 15px;
        padding: 10px;
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 8px;
        font-size: 14px;
        text-align: left;
    }
    /* FORM FIX */
    .profile-page form{
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;
    }
    .profile-page input{
        width: 100%;
        padding: 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
        text-align: center;
        letter-spacing: 2px;
    }
    .profile-page input:focus{
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .profile-page input::placeholder{
        color: #9ca3af;
        letter-spacing: normal;
    }
    .profile-page button{
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 10px;
        background: #2563eb;
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    .profile-page button:hover{
        background: #1d4ed8;
    }
    .profile-page button:active{
        transform: scale(0.98);
    }
    /* MOBILE */
    @media(max-width: 600px){
        .profile-page{
            margin: 20px;
            padding: 20px;
        }
        .profile-page h2{
            font-size: 24px;
        }
        .profile-page input{
            font-size: 14px;
        }
        .profile-page button{
            font-size: 15px;
        }
    }
</style>
<div class="profile-page">
    <h2>Verifikasi OTP Password</h2>
    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div>{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('profile.verify.password.otp') }}" method="POST">
        @csrf
        <input type="text" name="otp" placeholder="Masukkan OTP" required>
        <br><br>
        <button type="submit">
            Verifikasi OTP
        </button>
    </form>
</div>
@endsection
