<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $products = DB::table('products as p')
            ->select('p.id as product_id', 'p.*', 'c.category')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->get()->all();
        return view('product.index', [
            'title' => 'DnG Store | Produk',
            'menu' => ['Produk'],
            'user' => auth()->user(),
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create-product', [
            'title' => 'DnG Store | Create Product',
            'user' => auth()->user(),
            'menu' => ['Produk', 'Tambah'],
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
            'desc' => 'required',
            'uom' => 'required',
            'photo' => 'required',
            'qty' => 'required',
            'weight' => 'required',
            'customer_price' => 'required|numeric',
            'reseller_price' => 'required|numeric',
            'uom' => 'required|not_in:pilih',
            'status' => 'required|not_in:pilih',
            'category' => 'required|not_in:pilih',

        ], [
            'name.required' => 'Nama Produk wajib diisi!',
            'desc.required' => 'Deskripsi Produk wajib diisi!',
            'photo.required' => 'Foto Produk wajib diisi!',
            'qty.required' => 'Stok Produk wajib diisi!',
            'customer_price.required' => 'Harga Customer wajib diisi!',
            'reseller_price.required' => 'Harga Reseller wajib diisi!',
            'category.required' => 'Kategori Produk wajib diisi!',
            'uom.required' => 'Satuan Produk wajib diisi!',
            'weight.required' => 'Ukuran Produk wajib diisi!',
            'category.not_in' => 'Pilihan Kategori salah!',
            'status.not_in' => 'Pilihan status salah!',
            'uom.not_in' => 'Pilihan satuan salah!',
            // 'customer_price.numeric' => 'Pilihan satuan salah!',
        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('products')->insert([
            'name' => $request->name,
            'desc' => htmlspecialchars($request->desc),
            'customer_price' => $request->customer_price,
            'reseller_price' => $request->reseller_price,
            'photo' => $photo,
            'uom' => $request->uom,
            'weight' => $request->weight ? $request->weight : 0,
            'qty' => $request->qty ? $request->qty : 0,
            'qty_status' => $request->qty ? 'Ready' : 'Habis',
            'special_status' => $request->status,
            'category_id' => $request->category,
        ]);
        $session = [
            'message' => 'Berhasil menambahkan produk baru!',
            'type' => 'Tambah Produk',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('product')->with($session);
    }

    public function stock($id)
    {
        return view('product.product-stock', [
            'title' => 'DnG Store | Product Stock',
            'user' => auth()->user(),
            'menu' => ['Produk', 'Stok'],
            'product' => DB::table('products')->where('id', $id)->first()
        ]);
    }

    public function stockStore(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required',

        ]);
        DB::table('products')->where('id', $product->id)->update([
            'qty' => intval($product->qty + $request->qty),
            'status' => 'Ready',
        ]);
        $session = [
            'message' => "berhasil menambahkan stock pada $product->name!",
            'type' => 'Tambah Stock barang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('product')->with($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('product.detail-product', [
            'title' => 'DnG Store | Detail Produk',
            'user' => auth()->user(),
            'menu' => ['Produk', 'Detail'],
            'product' => Product::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('product.edit-product', [
            'title' => 'DnG Store | Edit Product',
            'user' => auth()->user(),
            'menu' => ['Produk', 'Edit'],
            'product' => DB::table('products')->where('id', $product->id)->first(),
            'categories' => DB::table('categories')->get()->all()
        ]);
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
        $request->validate([
            'name' => 'required',
            'customer_price' => 'required',
            'reseller_price' => 'required',
        ]);
        $photo = $request->file('photo');
        if ($photo) {
            $filephoto = $photo->store('image');
        } else {
            $filephoto = $product->photo;
        }
        DB::table('products')->where('id', $product->id)->update([
            'name' => $request->name,
            'desc' => htmlspecialchars($request->desc),
            'customer_price' => $request->customer_price,
            'reseller_price' => $request->reseller_price,
            'photo' => $filephoto,
            'uom' => $request->uom,
            'weight' => $request->weight ? $request->weight : 0,
            'category_id' => $request->category,
        ]);
        $session = [
            'message' => 'Berhasil mengupdate produk!',
            'type' => 'Tambah Produk',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('product')->with($session);
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
        return redirect()->route('product')->with($session);
    }
}
