<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Random;

class CartController extends Controller
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
        $user = auth()->user();
        $carts = Cart::where('user_id', $user->id)->get()->all();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('cart.index', [
            'title' => 'DnG Store | My Cart',
            'user' => $user,
            'menu' => ['Cart', 'Detail'],
            'carts' => $carts,
            'notifications' => $notification,
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
        DB::beginTransaction();
        DB::table('invoices')->insert([
            'user_id' => $user->id,
            'invoice_code' => 'INV-1003' . Random::generate(4, '0-9') . date('Y') * date('m') * date('d'),
            'grand_total' => 0,
            'status' => 'Pending',
            'created_at' => now('Asia/Jakarta')
        ]);
        $total = 0;
        $last_invoice = DB::table('invoices')->latest('id')->first();
        foreach ($request->cart as $key => $data) {
            $productId = $request->product_id[$key];
            $product = Product::where('id', $productId)->first();

            if ($request->qty[$key] > $product->qty) {
                $session = [
                    'message' => 'Anda tidak dapat memesan barang melebihi kuantitas yang tersedia!',
                    'type' => 'Checkout!',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                return redirect()->route('us.cart')->with($session);
            }

            $total += $request->total[$key];
            DB::table('orders')->insert([
                'user_id' => $user->id,
                'product_id' => $productId,
                'invoice_id' => $last_invoice->id,
                'qty' => $request->qty[$key],
                'total' => $request->total[$key],
                'created_at' => now('Asia/Jakarta')
            ]);
            DB::table('carts')->delete($key);
        }
        DB::table('invoices')->where('invoice_code', $last_invoice->invoice_code)->update([
            'grand_total' => $total,
            'updated_at' => now('Asia/Jakarta'),
        ]);
        DB::commit();
        return redirect()->route('us.invoice.edit', ['invoice' => $last_invoice->id])->with($session);
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
