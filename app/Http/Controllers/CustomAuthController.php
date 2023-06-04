<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        $user = DB::table('users')->where('email', $request->email)->first();
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            if ($user->role == 4) {
                session(['id' => $user->id, 'email' => $request->email, 'name' => $user->name]);
                return redirect()->route('home')->with(['message' => 'Selamat datang di DnG Store!', 'type' => 'Login', 'alert' => 'Notifikasi Sukses!', 'class' => 'success']);
            }
            if ($user->role == 3) {
                session(['id' => $user->id, 'email' => $request->email, 'name' => $user->name]);
                return redirect()->route('home')->with(['message' => 'Selamat datang di DnG Store!', 'type' => 'Login', 'alert' => 'Notifikasi Sukses!', 'class' => 'success']);
            }
            session(['id' => $user->id, 'email' => $request->email, 'name' => $user->name]);
            return redirect()->route('admin')->with(['message' => 'Selamat datang di DnG Store!', 'type' => 'Login', 'alert' => 'Notifikasi Sukses!', 'class' => 'success']);
        }
        return redirect()->back()->with(['message' => 'Email atau password salah, harap login kembali!', 'type' => 'Credentials Error', 'alert' => 'Notifikasi Gagal!', 'class' => 'success']);
    }

    // All proccess stored data here's
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|min:6',
            'repeat-password' => 'required|same:password|required_with:password'
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);
        $session = [
            'message' => 'Berhasil membuat akun!',
            'type' => 'Insert Data',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->intended('login')->with($session);
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
