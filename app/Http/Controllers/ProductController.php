<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.product', [
            'title' => 'DnG Store | Product',
            'user' => $user,
            'products' => Product::all(),
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
        return view('admin.create-product', [
            'title' => 'DnG Store | Create Product',
            'user' => $user,
            'categories' => Category::all(),
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
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'photo' => 'required|mimetypes:image/jpg,image/jpeg,image/png,image/gif|max:8192'
        ]);
        $photo = $request->file('photo');
        $fileName = $photo->getClientOriginalName();
        Storage::putFile('upload/img', $photo);
        DB::table('products')->insert([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'photo' => $fileName,
            'uom' => $request->uom,
            'weight' => $request->weight ? $request->weight : 0,
            'qty' => $request->qty ? $request->qty : 0,
            'status' => $request->qty ? 'Ready' : 'Tidak Ready',
            'category_id' => $request->category,
        ]);
        $session = [
            'message' => 'Berhasil menambahkan prodak baru!',
            'type' => 'Tambah Prodak',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->intended('product')->with($session);
    }

    public function stock($id)
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        return view('admin.product-stock', [
            'title' => 'DnG Store | Product Stock',
            'user' => $user,
            'product' => DB::table('products')->where('id', $id)->first()
        ]);
    }

    public function stockStore(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required',

        ]);
        DB::table('products')->where('id', $product->id)->update([
            'qty' => intval($product->qty + $request->qty)
        ]);
        $session = [
            'message' => "berhasil menambahkan stock pada $product->name!",
            'type' => 'Tambah Stock barang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->intended('product')->with($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::table('products')->delete($id);
        $session = [
            'message' => "berhasil menghapus data!",
            'type' => 'Hapus barang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->intended('product')->with($session);
    }
}
