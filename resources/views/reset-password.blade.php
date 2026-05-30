<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">
    <title>
        Ganti Password
    </title>
    <link rel="stylesheet"
          href="{{ asset('css/gantipassword.css') }}">
</head>
<body class="reset-page">
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    
    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="reset-header">
                <h2 class="reset-title">
                    Ganti Password
                </h2>
                <p class="reset-subtitle">
                    Password harus kuat dan aman
                </p>
            </div>
            <form
                method="POST"
                action="/reset-password"
                class="reset-form"
                id="resetForm">
                @csrf
                <div class="input-group">
                    <span class="input-icon">
                        🔒
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="password-input"
                        placeholder="Password Baru"
                        autocomplete="new-password"
                        required>
                </div>
                <div
                    id="passwordError"
                    class="password-error">
                </div>
                <div class="input-group">
                    <span class="input-icon">
                        🔑
                    </span>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="confirmPassword"
                        class="password-input"
                        placeholder="Konfirmasi Password"
                        autocomplete="new-password"
                        required>
                </div>
                <button
                    type="submit"
                    class="reset-button">
                    Simpan Password
                </button>
            </form>
        </div>
    </div>
    <script>
        function togglePassword(inputId, icon){
            const input = document.getElementById(inputId);
            const eye = icon.querySelector("i");
            if(input.type === "password"){
                input.type = "text";
                eye.classList.remove("fa-eye");
                eye.classList.add("fa-eye-slash");
            }else{
                input.type = "password";
                eye.classList.remove("fa-eye-slash");
                eye.classList.add("fa-eye");
            }
        }
        document.addEventListener(
            "DOMContentLoaded",
            function(){
                const fa = document.createElement("link");
                fa.rel = "stylesheet";
                fa.href =
                    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css";
                document.head.appendChild(fa);
                const form =
                    document.getElementById(
                        "resetForm"
                    );
                const password =
                    document.getElementById(
                        "password"
                    );
                const confirmPassword =
                    document.getElementById(
                        "confirmPassword"
                    );
                const errorBox =
                    document.getElementById(
                        "passwordError"
                    );
                const eye1 = document.createElement("span");
                eye1.className = "password-toggle";
                eye1.innerHTML =
                    '<i class="fa-regular fa-eye"></i>';
                eye1.onclick = function(){
                    togglePassword("password", eye1);
                };
                password.parentElement.appendChild(eye1);
                const eye2 = document.createElement("span");
                eye2.className = "password-toggle";
                eye2.innerHTML =
                    '<i class="fa-regular fa-eye"></i>';
                eye2.onclick = function(){
                    togglePassword("confirmPassword", eye2);
                };
                confirmPassword.parentElement.appendChild(eye2);
                form.addEventListener(
                    "submit",
                    function(e){
                        const pass =
                            password.value;
                        const confirm =
                            confirmPassword.value;
                        const regex =
                            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
                        if(
                            !regex.test(pass)
                        ){
                            e.preventDefault();
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
                        if(
                            pass !== confirm
                        ){
                            e.preventDefault();
                            errorBox.innerHTML = `
                                Password konfirmasi
                                tidak sama
                            `;
                            confirmPassword.focus();
                            return false;
                        }
                        errorBox.innerHTML = "";
                    }
                );
                password.addEventListener(
                    "input",
                    function(){
                        errorBox.innerHTML = "";
                    }
                );
                confirmPassword.addEventListener(
                    "input",
                    function(){
                        errorBox.innerHTML = "";
                    }
                );
            }
        );
    </script>
</body>
</html>
