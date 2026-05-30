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
        margin-bottom: 10px;
        color: #1f2937;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
    }
    .subtitle{
        margin-bottom: 25px;
        color: #6b7280;
        font-size: 14px;
        text-align: center;
    }
    .error-box{
        margin-bottom: 15px;
        padding: 10px;
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 8px;
        font-size: 14px;
        text-align: left;
    }
    /* FORM FIX */
    #resetForm{
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;
    }
    .input-group{
        position: relative;
        width: 100%;
    }
    .input-icon{
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        z-index: 2;
    }
    .password-input{
        width: 100%;
        padding: 14px 45px 14px 45px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .password-input:focus{
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .password-input::placeholder{
        color: #9ca3af;
    }
    /* EYE ICON */
    .password-toggle{
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6b7280;
        font-size: 18px;
        z-index: 2;
    }
    .password-toggle:hover{
        color: #2563eb;
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
    #passwordError{
        margin-bottom: 15px;
        padding: 10px;
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 8px;
        font-size: 14px;
        text-align: left;
        display: none;
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
        .subtitle{
            font-size: 13px;
        }
        .password-input{
            font-size: 14px;
            padding: 12px 42px 12px 42px;
        }
        .profile-page button{
            font-size: 15px;
            padding: 12px;
        }
    }
</style>
<div class="profile-page">
    <h2>Ganti Password</h2>
    <p class="subtitle">Password harus kuat dan aman</p>
    @if(session('error'))
        <div class="error-box">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif
    <form action="{{ route('profile.update.password') }}" method="POST" id="resetForm">
        @csrf
        <div class="input-group">
            <span class="input-icon">🔒</span>
            <input
                type="password"
                name="new_password"
                id="password"
                class="password-input"
                placeholder="Password Baru"
                autocomplete="new-password"
                required>
        </div>
        <div id="passwordError"></div>
        <div class="input-group">
            <span class="input-icon">🔑</span>
            <input
                type="password"
                name="new_password_confirmation"
                id="confirmPassword"
                class="password-input"
                placeholder="Konfirmasi Password Baru"
                autocomplete="new-password"
                required>
        </div>
        <button type="submit">
            Update Password
        </button>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){
    const form = document.getElementById("resetForm");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const errorBox = document.getElementById("passwordError");
    form.addEventListener("submit", function(e){
        const pass = password.value;
        const confirm = confirmPassword.value;
        const regex =
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
        /* PASSWORD RULE */
        if(!regex.test(pass)){
            e.preventDefault();
            errorBox.style.display = "block";
            errorBox.innerHTML = `
                Password harus memiliki:
                <br><br>
                ✓ Minimal 8 karakter
                <br>
                ✓ Huruf besar (A-Z)
                <br>
                ✓ Huruf kecil (a-z)
                <br>
                ✓ Angka (0-9)
                <br>
                ✓ Simbol (!@#$ dll)
            `;
            password.focus();
            return false;
        }
        if(pass !== confirm){
            e.preventDefault();
            errorBox.style.display = "block";
            errorBox.innerHTML = `
                Password konfirmasi tidak sama
            `;
            confirmPassword.focus();
            return false;
        }
        errorBox.style.display = "none";
        errorBox.innerHTML = "";
    });
    password.addEventListener("input", function(){
        errorBox.style.display = "none";
        errorBox.innerHTML = "";
    });
    confirmPassword.addEventListener("input", function(){
        errorBox.style.display = "none";
        errorBox.innerHTML = "";
    });
});
</script>
@endsection
