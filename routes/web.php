<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ModelGrafikController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');

    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

    Route::post('/transaksi', [DetailTransaksiController::class, 'store']);
    Route::get('/riwayat', [DetailTransaksiController::class, 'riwayat'])->name('riwayat');


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::get('/profile/change-email', [ProfileController::class, 'emailForm'])->name('profile.email.form');
    Route::post('/profile/send-old-email-otp', [ProfileController::class, 'sendOldEmailOtp'])->name('profile.send.old.email.otp');

    Route::get('/profile/old-email-otp', [ProfileController::class, 'oldEmailOtpForm'])->name('profile.email.old.otp.form');
    Route::post('/profile/verify-old-email-otp', [ProfileController::class, 'verifyOldEmailOtp'])->name('profile.verify.old.email.otp');

    Route::get('/profile/new-email', [ProfileController::class, 'newEmailForm'])->name('profile.new.email.form');
    Route::post('/profile/send-new-email-otp', [ProfileController::class, 'sendNewEmailOtp'])->name('profile.send.new.email.otp');


    Route::get('/profile/new-email-otp', [ProfileController::class, 'newEmailOtpForm'])->name('profile.email.new.otp.form');
    Route::post('/profile/verify-new-email-otp', [ProfileController::class, 'verifyNewEmailOtp'])->name('profile.verify.new.email.otp');

    Route::get('/profile/change-password', [ProfileController::class, 'passwordForm'])->name('profile.password.form');
    Route::post('/profile/send-password-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.send.password.otp');

    Route::get('/profile/password-otp', [ProfileController::class, 'passwordOtpForm'])->name('profile.password.otp.form');
    Route::post('/profile/verify-password-otp', [ProfileController::class, 'verifyPasswordOtp'])->name('profile.verify.password.otp');

    Route::get('/profile/new-password', [ProfileController::class, 'newPasswordForm'])->name('profile.new.password.form');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

    Route::get('/modelgrafik', [ModelGrafikController::class, 'Grafik'])->name('modelgrafik');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'formEmail'])->name('forgot.form');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.send');

Route::get('/otp', [ForgotPasswordController::class, 'formOtp'])->name('otp.form');
Route::post('/otp', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/reset-password', [ForgotPasswordController::class, 'formReset'])->name('reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password');
