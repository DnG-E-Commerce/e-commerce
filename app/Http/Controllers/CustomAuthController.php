<?php

namespace App\Http\Controllers;

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
            session(['id' => $user->id, 'email' => $request->email, 'name' => $user->name]);
            $session = [
                'message' => 'Selamat datang di DnG Store!',
                'type' => 'Login',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            switch ($user->role) {
                case 'Owner':
                    return redirect()->route('owner')->with($session);
                    break;

                case 'Admin':
                    return redirect()->route('admin')->with($session);
                    break;

                case 'Driver':
                    return redirect()->route('driver')->with($session);
                    break;

                case 'Reseller':
                    return redirect()->route('home')->with($session);
                    break;

                case 'Customer':
                    return redirect()->route('home')->with($session);
                    break;
            }
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
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'Customer',
            'created_at' => now('Asia/Jakarta')
        ]);
        $latest = DB::table('users')->latest('id')->first();
        DB::table('notifications')->insert([
            'user_id' => $latest->id,
            'title' => 'Membuat Akun baru',
            'message' => "Berhasil menbuat akun baru pada " . now('Asia/Jakarta'),
            'is_read' => 1
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
