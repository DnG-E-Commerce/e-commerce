<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\map;

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
    public function index(Request $request)
    {
        $query = $request->search;
        if ($query) {
            $products = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.id')->where('products', 'LIKE', "%$query%")->orWhere('category', 'LIKE', "%$query%")->get()->all();
        } else {
            $products = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.id')->get()->all();
        }
        return view('admin.product', [
            'title' => 'DnG Store | Product',
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
        return view('admin.create-product', [
            'title' => 'DnG Store | Create Product',
            'user' => auth()->user(),
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
            'customer_price' => 'required',
            'reseller_price' => 'required',
            'photo' => 'required|image|file|max:8192'
        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('products')->insert([
            'name' => $request->name,
            'desc' => $request->desc,
            'customer_price' => $request->customer_price,
            'reseller_price' => $request->reseller_price,
            'photo' => $photo,
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
        return redirect()->route('product')->with($session);
    }

    public function stock($id)
    {
        return view('admin.product-stock', [
            'title' => 'DnG Store | Product Stock',
            'user' => auth()->user(),
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
            'status' => 'ready',
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
        return view('admin.edit-product', [
            'title' => 'DnG Store | Edit Product',
            'user' => auth()->user(),
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
            'photo' => 'required|image|file|max:8192'
        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('products')->where('id', $product->id)->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'customer_price' => $request->customer_price,
            'reseller_price' => $request->reseller_price,
            'photo' => $photo,
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
