<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index', [
            'title' => 'DnG Store | My Cart',
            'user' => auth()->user(),
            'menu' => ['Cart', 'Detail'],
            'carts' => Cart::where('user_id', session('id'))->get()->all(),
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
        $user = auth()->user();
        $session = [
            'message' => 'Berhasil menambahkan prodak ke keranjang, Checkout sekarang juga!',
            'type' => 'Tambah ke Keranjang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        $cart = DB::table('carts')->where([
            ['user_id', '=', $user->id],
            ['product_id', '=', $product->id]
        ])->first();
        if ($cart) {
            DB::table('carts')->where([
                ['user_id', '=', $user->id],
                ['product_id', '=', $product->id]
            ])->update([
                'qty' => $cart->qty + $request->qty,
                'total' => $cart->total + ($request->qty * $request->price)
            ]);
        }
        DB::table('carts')->insert([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'qty' => $request->qty,
            'total' => ($request->qty * $request->price)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */

    public function checkout(Request $request)
    {
        $user = auth()->user();

        $session = [
            'message' => 'Berhasil melakukan checkout, lakukan pembayaran sekarang juga!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        foreach ($request->cart as $key => $data) {
            $productId = $request->input('product_id')[$key];
            $qty = $request->input('qty')[$key];
            $total = $request->input('total')[$key];
            $order = DB::table('orders')->where([
                ['status', '=', null],
                ['user_id', '=', "$user->id"],
                ['product_id', '=', "$productId"]
            ])->first();
            if (!$order) {
                DB::table('orders')->insert([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'total' => $total,
                ]);
            } else {
                DB::table('orders')->where([
                    ['user_id', '=', "$user->id"],
                    ['product_id', '=', "$productId"],
                    ['status', '=', null]
                ])->update([
                    'qty' => $order->qty + $qty,
                    'total' => $total
                ]);
            }
            DB::table('carts')->delete($key);
        }
        return redirect()->route('order')->with($session);
    }

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
