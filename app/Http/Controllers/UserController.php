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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storePengajuan(Request $request, User $user)
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
        return redirect()->route('home')->with($session);
    }


    public function reseller(Request $request)
    {
        $query = $request->search;
        if ($query) {
            $resellers = DB::table('users')->where([
                ['role', '=', 'Reseller'],
                ['name', 'LIKE', "%$query%"]
            ])->get()->all();
        } else {
            $resellers = DB::table('users')->where('role', 'Reseller')->get()->all();
        }
        return view('reseller.index', [
            'title' => 'DnG Store | Reseller',
            'user' => auth()->user(),
            'menu' => ['Reseller'],
            'resellers' => $resellers
        ]);
    }

    public function customer(Request $request)
    {
        $query = $request->search;
        if ($query) {
            $customers = DB::table('users')->where([
                ['role', '=', 'Customer'],
                ['name', 'LIKE', "%$query%"]
            ])->get()->all();
        } else {
            $customers = DB::table('users')->where('role', 'Customer')->get()->all();
        }
        return view('customer.index', [
            'title' => 'DnG Store | Customer',
            'user' => auth()->user(),
            'menu' => ['Customer'],
            'customers' => $customers,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerCreate()
    {
        return view('customer.create-customer', [
            'title' => 'DnG Store | Tambah Customer',
            'user' => auth()->user(),
            'menu' => ['Customer', 'Tambah'],
        ]);
    }

    public function resellerCreate()
    {
        return view('reseller.create-reseller', [
            'title' => 'DnG Store | Tambah Reseller',
            'user' => auth()->user(),
            'menu' => ['Reseller', 'Tambah']
        ]);
    }

    public function customerStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'min:6|required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'phone' => 'required|numeric',
            'photo' => 'image|file|required|max:8192'
        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'Customer',
            'photo' => $photo
        ]);
        $session = [
            'message' => 'Berhasil menambahkan customer baru!',
            'type' => 'Tambah Customer',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('customer')->with($session);
    }

    public function resellerStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'min:6|required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'photo' => 'image|file|required|max:8192'
        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'Reseller',
            'photo' => $photo
        ]);
        $session = [
            'message' => 'Berhasil menambahkan customer baru!',
            'type' => 'Tambah Customer',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('reseller')->with($session);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $select_user = DB::table('users')->where('id', $id)->first();
        return view('detail-user', [
            'title' => 'DnG Store | Detail',
            'user' => auth()->user(),
            'menu' => $select_user->role == 'Customer' ? ['Customer', 'Detail'] : ['Reseller', 'Detail'],
            'select_user' => $select_user,
            'role' => ['Owner', 'Admin', 'Drive', 'Reseller', 'Customer']
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function review(User $user)
    {
        $request_upgrade = DB::table('request_upgrades')->where('user_id', $user->id)->first();
        return view('customer.review-request', [
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
        return redirect()->route('customer')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
