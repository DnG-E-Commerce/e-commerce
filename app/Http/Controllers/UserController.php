<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createCustomer()
    {
        return view('user.customer.create-customer', [
            'title' => 'DnG Store | Tambah Customer',
            'user' => auth()->user(),
            'menu' => ['Customer', 'Tambah'],
        ]);
    }

    public function createReseller()
    {
        return view('user.reseller.create-reseller', [
            'title' => 'DnG Store | Tambah Reseller',
            'user' => auth()->user(),
            'menu' => ['Reseller', 'Tambah']
        ]);
    }

    public function createAdmin()
    {
        return view('user.admin.create-admin', [
            'title' => 'DnG Store | Tambah Admin Atau Driver',
            'user' => auth()->user(),
            'menu' => ['Admin', 'Tambah']
        ]);
    }

    public function storeUser(Request $request, $role)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'min:6|required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'phone' => 'required|numeric|unique:users',
            'photo' => 'image|file|required|max:8192'
        ]);
        $photo = $request->file('photo')->store('image');
        switch ($role) {
            case 'customer':
                $role = 'Customer';
                $email_verif = null;
                break;

            case 'reseller':
                $role = 'Reseller';
                $email_verif = null;
                break;

            case 'admin-driver':
                $role = $request->role;
                $email_verif = now('Asia/Jakarta');
                break;
        }
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi",
            'phone' => $request->phone,
            'role' => $role,
            'photo' => $photo,
            'email_verified_at' => $email_verif
        ]);
        $session = [
            'message' => 'Berhasil menambahkan customer baru!',
            'type' => 'Tambah Customer',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        if ($role == 'Reseller') return redirect()->route('su.reseller')->with($session);
        if ($role == 'Admin' || $role == 'Driver') return redirect()->route('su.admin')->with($session);
        return redirect()->route('su.customer')->with($session);
    }

    public function store(Request $request)
    {
        //
    }

    public function profileUser($role, User $user)
    {
        return view('user.detail-user', [
            'title' => 'DnG Store | Detail',
            'menu' => $user->role == 'Customer' ? ['Customer', 'Detail'] : ['Reseller', 'Detail'],
            'role' => ['Owner', 'Admin', 'Drive', 'Reseller', 'Customer'],
            'user' => auth()->user(),
            'select_user' => $user,
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function review(User $user)
    {
        $request_upgrade = DB::table('request_upgrades')->where('user_id', $user->id)->first();
        return view('user.customer.review-request', [
            'title' => 'DnG Store | Review Request',
            'user' => auth()->user(),
            'select_user' => $user,
            'review' => $request_upgrade,
            'menu' => ['Cusomter', 'Review Request'],
        ]);
    }

    public function acceptRequest(Request $request, User $user)
    {
        DB::table('request_upgrades')->where('user_id', $request->req_id)->update([
            'updated_at' => now('Asia/Jakarta'),
        ]);
        DB::table('users')->where('id', $user->id)->update([
            'role' => 'reseller',
            'request_upgrade' => 0
        ]);
        $session = [
            'message' => 'Berhasil mengupgrade customer!',
            'type' => 'Upgrade Customer',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.customer')->with($session);
    }

    public function destroy($id)
    {
        //
    }

    public function storeRequest(Request $request, User $user)
    {
        $request->validate([
            'instance_name' => 'required',
            'photo' => 'image|max:8149',
        ]);
        $photo = $request->file('photo')->store('instance');
        DB::table('users')->where('id', $user->id)
            ->update([
                'request_upgrade' => 1
            ]);
        DB::table('request_upgrades')->insert([
            'user_id' => $user->id,
            'instance_name' => $request->instance_name,
            'photo' => $photo,
            'created_at' => now('Asia/Jakarta')
        ]);
        DB::table('notifications')->insert([
            'user_id' => $user->id,
            'title' => 'Pengajuan',
            'message' => "Pengajuan untuk Menjadi Reseller telah berhasil, harap tunggu untuk konfirmasi dari Admin",
            'is_read' => 0,
            'created_at' => now('Asia/Jakarta')
        ]);
        $session = [
            'message' => 'Berhasil mengajukan menjadi reseller, harap menunggu konfirmasi dari admin',
            'type' => 'Pengajuan Berhasil',
            'alert' => 'Notifikasi berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('us.home')->with($session);
    }
}
