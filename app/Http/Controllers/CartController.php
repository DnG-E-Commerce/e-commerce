<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return view('cart.index', [
            'title' => 'DnG Store | My Cart',
            'user' => auth()->user(),
            'menu' => ['Cart', 'Detail'],
            'carts' => Cart::where('user_id', $user->id)->get()->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required'
        ]);
        DB::table('carts')->insert([
            'user_id' => session('id'),
            'product_id' => $product->id,
            // 'send_to' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi",
            'qty' => $request->qty,
            'total' => ($request->qty * $request->price)
        ]);
        $session = [
            'message' => 'Berhasil menambahkan prodak ke keranjang, Checkout sekarang juga!',
            'type' => 'Tambah ke Keranjang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('home.product', ['product' => $product])->with($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::table('carts')->delete($id);
        $session = [
            'message' => "Berhasil menghapus data!",
            'type' => 'Hapus barang dikeranjang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('cart')->with($session);
    }
    
}
