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
    public function index()
    {
        //
    }

    public function reseller(Request $request)
    {
        $query = $request->search;
        if ($query) {
            $resellers = DB::table('users')->where([
                ['role', '=', 3],
                ['name', 'LIKE', "%$query%"]
            ])->get()->all();
        } else {
            $resellers = DB::table('users')->where('role', 3)->get()->all();
        }
        return view('admin.reseller', [
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
                ['role', '=', 4],
                ['name', 'LIKE', "%$query%"]
            ])->get()->all();
        } else {
            $customers = DB::table('users')->where('role', 4)->get()->all();
        }
        return view('admin.customer', [
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
        return view('admin.create-customer', [
            'title' => 'DnG Store | Tambah Customer',
            'user' => auth()->user(),
            'menu' => ['Customer', 'Tambah'],
        ]);
    }

    public function resellerCreate()
    {
        return view('admin.create-reseller', [
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
            'role' => 4,
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
            'role' => 3,
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

    public function customerShow($id)
    {
        $customer = DB::table('users')->where('id', $id)->first();
        return view('admin.detail-user', [
            'title' => 'DnG Store | Detail',
            'user' => auth()->user(),
            'menu' => ['Customer', 'Detail'],
            'customer' => $customer,
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customer']
        ]);
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
        return view('admin.detail-user', [
            'title' => 'DnG Store | Detail',
            'user' => auth()->user(),
            'menu' => ['Profile'],
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
    public function update(Request $request, User $user)
    {
        DB::table('users')->where('id', $user->id)->update([
            'role' => 3,
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
