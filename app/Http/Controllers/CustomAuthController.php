<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login', [
            'title' => 'DnG Store | Login',
        ]);
    }

    // Method Custom Registration
    public function create()
    {
        return view('auth.register', [
            'title' => 'DnG Store | Register'
        ]);
    }

    public function credentials(Request $request)
    {
        // dd($request->rememberMe);
        $user = DB::table('users')->where('email', $request->email)->first();
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            if (!auth()->user()->email_verified_at) {
                session()->flush();
                Auth::logout();
                $message = "Akun anda belum terverifikasi, harap lakukan verifikasi
                    <div class='d-flex justify-content-center'>
                        <a class='btn btn-sm btn-success' href='/login/send-otp/$user->id'>Verifikasi</a>
                    </div>
                    ";
                $session = [
                    'message' => $message,
                    'type' => 'Login',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                return redirect()->route('login')->with($session);
            }
            session(['id' => $user->id, 'email' => $request->email, 'name' => $user->name]);
            $session = [
                'message' => 'Selamat datang di DnG Store!',
                'type' => 'Login',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            if (in_array($user->role, ['Owner', 'Admin'])) return redirect()->route('su.dashboard')->with($session);
            if ($user->role == 'Driver') return redirect()->route('su.delivery')->with($session);
            return redirect()->route('us.home')->with($session);
        }
        return redirect()->back()->with(['message' => 'Email atau password salah, harap login kembali!', 'type' => 'Credentials Error', 'alert' => 'Notifikasi Gagal!', 'class' => 'success']);
    }

    public function sendOTPFromLogin(User $user)
    {
        $check = DB::table('email_verifications')->where('email', $user->email)->first();
        $otp = mt_rand(100000, 999999);
        $data = [
            'from' => 'dngstore.admin@gmail.com',
            'to' => $user->email,
            'title' => 'Email Verification',
            'body' => "Kode OTP Anda : $otp"
        ];
        Mail::send('auth.mail-otp', ['data' => $data], function ($message) use ($data) {
            $message->from($data['from'])->to($data['to'])->subject($data['title']);
        });
        if ($check) {
            DB::table('email_verifications')->where('email', $user->email)->update([
                'otp' => $otp,
                'updated_at' => now('Asia/Jakarta'),
            ]);
        } else {
            DB::table('email_verifications')->insert([
                'email' => $user->email,
                'otp' => $otp,
                'created_at' => now('Asia/Jakarta'),
            ]);
        }
        $session = [
            'message' => 'Harap cek email untuk mendapatkan OTP',
            'type' => 'Email Verifikasi',
            'alert' => 'Notifikasi!',
            'class' => 'success'
        ];
        return redirect()->route('email-verification')->with($session);
    }

    // All proccess stored data here's
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users|min:11',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'repeat-password' => 'required|same:password|required_with:password'
        ]);
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'Customer',
            'created_at' => now('Asia/Jakarta')
        ]);
        $latest = DB::table('users')->latest('id')->first();
        $otp = mt_rand(100000, 999999);
        $data = [
            'from' => 'dngstore.admin@gmail.com',
            'to' => $request->email,
            'title' => 'Email Verification',
            'body' => "Kode OTP Anda : $otp"
        ];
        Mail::send('auth.mail-otp', ['data' => $data], function ($message) use ($data) {
            $message->from($data['from'])->to($data['to'])->subject($data['title']);
        });
        DB::table('notifications')->insert([
            'user_id' => $latest->id,
            'title' => 'Membuat Akun baru',
            'message' => "Berhasil membuat akun baru pada " . now('Asia/Jakarta'),
            'is_read' => 1
        ]);

        DB::table('email_verifications')->insert([
            'email' => $latest->email,
            'otp' => $otp,
            'created_at' => now('Asia/Jakarta'),
        ]);

        $session = [
            'message' => 'Berhasil membuat akun! Harap untuk lakukan verifikasi email untuk melanjutkannya!',
            'type' => 'Insert Data',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('email-verification')->with($session);
    }

    public function emailVerification()
    {
        return view('auth.email-verification', [
            'title' => "DnG Store | Verifikasi Email",
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);
        $data = DB::table('email_verifications')->where('otp', $request->otp)->first();
        if ($data !== null && $request->otp == $data->otp) {
            DB::table('users')->where('email', $data->email)->update([
                'email_verified_at' => now('Asia/Jakarta')
            ]);
            DB::table('email_verifications')->delete($data->id);
            $session = [
                'message' => 'Berhasil memverifikasi email! Login untuk mulai berbelanja',
                'type' => 'Email Verifikasi',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            return redirect()->route('login')->with($session);
        }
        $session = [
            'message' => 'OTP yang anda inputkan tidak sesuai, harap cek kembali email anda!',
            'type' => 'Email Verifikasi',
            'alert' => 'Notifikasi Gagal!',
            'class' => 'danger'
        ];
        return redirect()->route('email-verification')->with($session);
    }

    public function logout()
    {
        session()->flush();
        Auth::logout();
        $session = [
            'message' => 'Berhasil keluar dari DnG Store!',
            'type' => 'Logout Success',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return Redirect()->route('login')->with($session);
    }
}
