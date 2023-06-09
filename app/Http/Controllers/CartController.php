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
        $session = [
            'message' => 'Berhasil menambahkan prodak ke keranjang, Checkout sekarang juga!',
            'type' => 'Tambah ke Keranjang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];

        $cart = DB::table('carts')->where([
            ['user_id', '=', session('id')],
            ['product_id', '=', $product->id]
        ])->first();

        $request->validate([
            'qty' => 'required'
        ]);

        if ($cart) {
            DB::table('carts')->where([
                ['user_id', '=', session('id')],
                ['product_id', '=', $product->id]
            ])->update([
                'qty' => $cart->qty + $request->qty,
                'total' => $cart->total + ($request->qty * $request->price)
            ]);
            return response()->json([
                'route' => 'home.product',
                'product' => $product->id,
                'message' => 'Berhasil menambahkan prodak ke keranjang, Checkout sekarang juga!',
                'type' => 'Tambah ke Keranjang',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ]);
        }
        DB::table('carts')->insert([
            'user_id' => session('id'),
            'product_id' => $product->id,
            'qty' => $request->qty,
            'total' => ($request->qty * $request->price)
        ]);
        return response()->json([
            'route' => 'home.product',
            'product' => $product->id,
            'message' => 'Berhasil menambahkan prodak ke keranjang, Checkout sekarang juga!',
            'type' => 'Tambah ke Keranjang',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ]);
        // return redirect()->route('home.product', ['product' => $product->id])->with($session);
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
                ['status', '=', 'Unpaid'],
                ['user_id', '=', "$user->id"],
                ['product_id', '=', "$productId"]
            ])->first();

            if (!$order) {
                DB::table('orders')->insert([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'total_price' => $total,
                    'status' => 'Unpaid'
                ]);
            } else {
                DB::table('orders')->where([
                    ['user_id', '=', "$user->id"],
                    ['product_id', '=', "$productId"],
                    ['status', '=', "Unpaid"]
                ])->update([
                    'qty' => $order->qty + $qty,
                    'total_price' => $total
                ]);
            }

            DB::table('carts')->delete($key);
            // dd($order);
            // if ($order) {
            //     if ($order->user_id == $user->id && $order->product_id == $productId) {
            //         DB::table('orders')->where([
            //             ['user_id', '=', "$user->id"],
            //             ['product_id', '=', "$productId"]
            //         ])->update([
            //             'qty' => $order->qty + $qty,
            //             'total_price' => $total
            //         ]);
            //     }
            // } else {
            //     DB::insert([
            //         'user_id' => $user->id,
            //         'product_id' => $productId,
            //         'qty' => $qty,
            //         'total_price' => $total,
            //         'status' => 'Unpaid'
            //     ]);
            // }
        }
        return redirect()->route('cart')->with($session);
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
