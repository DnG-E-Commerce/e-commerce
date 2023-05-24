<?php

namespace App\Http\Controllers;

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

    public function reseller()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.reseller', [
            'title' => 'DnG Store | Reseller',
            'user' => $user,
            'resellers' => DB::table('users')->where('role', 3)->get()->all()
        ]);
    }

    public function customer()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.customer', [
            'title' => 'DnG Store | Customer',
            'user' => $user,
            'customers' => DB::table('users')->where('role', 4)->get()->all()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerCreate()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.create-customer', [
            'title' => 'DnG Store | Tambah Customer',
            'user' => $user
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
        return redirect()->route('user.customer')->with($session);
    }

    public function customerShow($id)
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        $customer = DB::table('users')->where('id', $id)->first();
        return view('admin.detail-customer', [
            'title' => 'DnG Store | Detail',
            'user' => $user,
            'customer' => $customer,
            'role' => ['Owner', 'Admin', 'Reseller', 'Customer']
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
        //
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
    public function update(Request $request, $id)
    {
        //
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
