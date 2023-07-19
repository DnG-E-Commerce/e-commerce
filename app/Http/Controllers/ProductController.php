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
        $this->middleware('auth')->except('usDetailProduct');
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
            'photo' => 'required|mimes:jpg,png,jpeg,bmp|max:1024',
            'qty' => 'required',
            'customer_price' => 'required|numeric',
            'reseller_price' => 'required|numeric',
            'uom' => 'required|not_in:pilih',
            'status' => 'required|not_in:pilih',
            'category' => 'required|not_in:pilih',

        ]);
        $photo = $request->file('photo')->store('image');
        DB::table('products')->insert([
            'name' => $request->name,
            'desc' => htmlspecialchars($request->desc),
            'customer_price' => $request->customer_price,
            'reseller_price' => $request->reseller_price,
            'photo' => $photo,
            'uom' => $request->uom,
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
        return redirect()->route('su.product')->with($session);
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
            'qty_status' => 'Ready',
        ]);
        $session = [
            'message' => "berhasil menambahkan stock pada $product->name!",
            'type' => 'Tambah Stock barang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.product')->with($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function usDetailProduct(Product $product)
    {
        $user = auth()->user();
        $notification = null;
        if ($user) {
            $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        }
        return view('home.detail-product', [
            'title' => 'DnG Store | Detail Product',
            'menu' => ['Product', 'Detail'],
            'user' => $user,
            'product' => $product,
            'notifications' => $notification
        ]);
    }

    public function suDetailProduct(Product $product)
    {
        $user = auth()->user();
        return view('product.detail-product', [
            'title' => 'DnG Store | Detail Produk',
            'menu' => ['Produk', 'Detail'],
            'user' => $user,
            'product' => $product,
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
    public function ubah(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'uom' => 'required|not_in:pilih',
            
           
            'customer_price' => 'required|numeric',
            'reseller_price' => 'required|numeric',
          
            'special_status' => 'required|not_in:pilih',
            'category' => 'required|not_in:pilih',
           
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
            'special_status' => $request->special_status,
            'uom' => $request->uom,
           
            'special_status' => $request->special_status,
            'category_id' => $request->category,
        ]);
        $session = [
            'message' => 'Berhasil mengupdate produk!',
            'type' => 'Tambah Produk',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.product')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('products')->delete($id);
        $session = [
            'message' => "berhasil menghapus data!",
            'type' => 'Hapus barang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.product')->with($session);
    }

    public function delete($id)
    {
        try {
            $Produk = Product::findOrFail($id);

            // Cek apakah ada relasi produk yang terhubung dengan kategori ini
            if ($Produk->order()->exists()) {
                throw new \Exception('Tidak dapat menghapus kategori ini karena masih terhubung dengan produk.');
            }

            $Produk->delete();
            $session = [
                'message' => 'Berhasil menghapus produk!',
                'type' => 'Hapus Produk',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            return redirect()->route('su.product')->with($session);
        } catch (\Exception) {
            $session = [
                'message' => 'Tidak dapat menghapus produk ini karena masih terhubung dengan pesanan',
                'type' => 'Hapus Produk',
                'alert' => 'Notifikasi Gagal!',
                'class' => 'success'
            ];
            return redirect()->back()->with($session);
        }
    }
}
