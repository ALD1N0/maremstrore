<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
class ForgotPasswordController extends Controller
{
    public function formEmail()
    {
        return view('auth.forgot-email');
    }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar');
        }
        $otp = rand(100000, 999999);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );
        session(['email' => $request->email]);
        Mail::raw("Kode OTP kamu: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Kode OTP Reset Password');
        });
        session(['email' => $request->email]);
        return redirect()->route('otp.form');
    }
    public function formOtp()
    {
        if (!session('email')) {
            return redirect()->route('forgot.form')
                ->with('error', 'Akses tidak valid');
        }
        return view('auth.otp');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);
        if (!session('email')) {
            return redirect()->route('forgot.form')
                ->with('error', 'Session hilang, ulangi lagi');
        }
        $data = DB::table('password_resets')
            ->where('email', session('email'))
            ->where('otp', $request->otp)
            ->first();
        if (!$data) {
            return back()->withErrors(['otp' => 'OTP salah'])->withInput();
        }
        session(['otp_verified' => true]);
        return redirect()->route('reset.form');
    }
    public function formReset()
    {
        if (!session('otp_verified')) {
            return redirect()->route('forgot.form')
                ->with('error', 'Akses ditolak, silakan ulangi proses');
        }
        return view('reset-password');
    }
    public function resetPassword(Request $request)
    {
        if (!session('otp_verified')) {
            return redirect()->route('forgot.form')
                ->with('error', 'Akses tidak valid');
        }
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);
        DB::table('users')
            ->where('email', session('email'))
            ->update([
                'password' => Hash::make($request->password)
            ]);
        DB::table('password_resets')
            ->where('email', session('email'))
            ->delete();
        session()->forget(['otp_verified', 'email']);
        return redirect('/login')->with('success', 'Password berhasil diubah');
    }
}
