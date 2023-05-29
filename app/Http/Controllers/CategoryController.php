<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $request->search;
        if ($query) {
            $categories = DB::table('categories')->where('category', 'LIKE', "%$query%")->get()->all();
        } else {
            $categories = DB::table('categories')->get()->all();
        }
        return view('admin.category', [
            'title' => 'DnG Store | Category',
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.create-category', [
            'title' => 'DnG Store | Tambah Category',
            'user' => $user,
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
        return view('admin.edit-category', [
            'title' => 'DnG Store | Edit',
            'user' => auth()->user(),
            'category' => DB::table('categories')->where('id', $id)->first(),
        ]);
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
        $request->validate([
            'category' => 'required',
        ]);
        DB::table('categories')->where('id', $id)->update([
            'category' => $request->category
        ]);
        $session = [
            'message' => 'Berhasil mengedit kategori!',
            'type' => 'Edit Kategori',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('category')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('categories')->delete($id);
        $session = [
            'message' => 'Berhasil menambahkan kategori baru!',
            'type' => 'Tambah Kategori',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('category')->with($session);
    }
}
