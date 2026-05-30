<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function emailForm()
    {
        return view('profile.change-email');
    }

    public function sendOldEmailOtp()
    {
        $user = Auth::user();

        $otp = random_int(100000, 999999);

        DB::table('email_verifications')->updateOrInsert(
            ['email' => $user->email],
            [
                'otp' => $otp,
                'created_at' => now()
            ]
        );

        Mail::raw("Kode OTP verifikasi email lama: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('OTP Verifikasi Email Lama');
        });

        session([
            'old_email' => $user->email
        ]);

        return redirect()->route('profile.email.old.otp.form');
    }

    public function oldEmailOtpForm()
    {
        if (!session('old_email')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Akses tidak valid');
        }

        return view('profile.old-email-otp');
    }

    public function verifyOldEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        if (!session('old_email')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Session hilang');
        }

        $data = DB::table('email_verifications')
            ->where('email', session('old_email'))
            ->where('otp', $request->otp)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if (!$data) {
            return back()->withErrors([
                'otp' => 'OTP salah atau expired'
            ]);
        }

        session([
            'old_email_verified' => true
        ]);

        return redirect()->route('profile.new.email.form');
    }

    public function newEmailForm()
    {
        if (!session('old_email_verified')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Akses ditolak');
        }

        return view('profile.new-email');
    }

    public function sendNewEmailOtp(Request $request)
    {
        if (!session('old_email_verified')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Akses tidak valid');
        }

        $request->validate([
            'new_email' => 'required|email|unique:users,email'
        ]);

        $otp = random_int(100000, 999999);

        DB::table('new_email_verifications')->updateOrInsert(
            ['new_email' => $request->new_email],
            [
                'otp' => $otp,
                'created_at' => now()
            ]
        );

        Mail::raw("Kode OTP verifikasi email baru: $otp", function ($message) use ($request) {
            $message->to($request->new_email)
                ->subject('OTP Verifikasi Email Baru');
        });

        session([
            'new_email' => $request->new_email
        ]);

        return redirect()->route('profile.email.new.otp.form');
    }

    public function newEmailOtpForm()
    {
        if (!session('new_email')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Akses tidak valid');
        }

        return view('profile.new-email-otp');
    }

    public function verifyNewEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        if (!session('new_email')) {
            return redirect()->route('profile.email.form')
                ->with('error', 'Session hilang');
        }

        $data = DB::table('new_email_verifications')
            ->where('new_email', session('new_email'))
            ->where('otp', $request->otp)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if (!$data) {
            return back()->withErrors([
                'otp' => 'OTP salah atau expired'
            ]);
        }

        DB::table('users')
            ->where('email', session('old_email'))
            ->update([
                'email' => session('new_email')
            ]);

        DB::table('email_verifications')
            ->where('email', session('old_email'))
            ->delete();

        DB::table('new_email_verifications')
            ->where('new_email', session('new_email'))
            ->delete();

        session()->forget([
            'old_email',
            'old_email_verified',
            'new_email'
        ]);

        return redirect()->route('profile')
            ->with('success', 'Email berhasil diubah');
    }

    public function passwordForm()
    {
        return view('profile.change-password');
    }

    public function sendPasswordOtp(Request $request)
    {
        $request->validate([
            'old_password' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $otp = random_int(100000, 999999);

        DB::table('password_change_verifications')->updateOrInsert(
            ['email' => $user->email],
            [
                'otp' => $otp,
                'created_at' => now()
            ]
        );

        Mail::raw("Kode OTP ubah password: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('OTP Ubah Password');
        });

        session([
            'password_email' => $user->email
        ]);

        return redirect()->route('profile.password.otp.form');
    }

    public function passwordOtpForm()
    {
        if (!session('password_email')) {
            return redirect()->route('profile.password.form')
                ->with('error', 'Akses tidak valid');
        }

        return view('profile.password-otp');
    }

    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $data = DB::table('password_change_verifications')
            ->where('email', session('password_email'))
            ->where('otp', $request->otp)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if (!$data) {
            return back()->withErrors([
                'otp' => 'OTP salah atau expired'
            ]);
        }

        session([
            'password_otp_verified' => true
        ]);

        return redirect()->route('profile.new.password.form');
    }

    public function newPasswordForm()
    {
        if (!session('password_otp_verified')) {
            return redirect()->route('profile.password.form')
                ->with('error', 'Akses ditolak');
        }

        return view('profile.new-password');
    }

    public function updatePassword(Request $request)
    {
        if (!session('password_otp_verified')) {
            return redirect()->route('profile.password.form')
                ->with('error', 'Akses tidak valid');
        }

        $request->validate([
            'new_password' => 'required|min:8|confirmed'
        ]);

        DB::table('users')
            ->where('email', session('password_email'))
            ->update([
                'password' => Hash::make($request->new_password)
            ]);

        DB::table('password_change_verifications')
            ->where('email', session('password_email'))
            ->delete();

        session()->forget([
            'password_email',
            'password_otp_verified'
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password berhasil diubah');
    }
}
