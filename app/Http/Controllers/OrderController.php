<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Random;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $orders = Order::where([
            ['user_id', '=', "$user->id"],
        ])->get()->all();
        return view('checkout.index', [
            'title' => 'DnG Store | Checkout',
            'user' => $user,
            'orders' => $orders,
            'menu' => ['Order']
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $cart = DB::table('carts')->where([
            ['user_id', '=', $user->id],
            ['product_id', '=', $request->product_id]
        ])->first();
        if ($request->mode == 'checkout') {
            DB::table('orders')->insert([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'total_price' => intval($request->qty * $request->price),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $session = [
                'message' => 'Harap lengkapi data-data untuk melanjutkan transaksi!',
                'type' => 'Checkout!',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            return redirect()->route('order')->with($session);
        } else {
            if ($cart) {
                DB::table('carts')->where([
                    ['user_id', '=', $user->id],
                    ['product_id', '=', $request->product_id]
                ])->update([
                    'qty' => $cart->qty + $request->qty,
                    'total' => $cart->total + ($request->qty * $request->price)
                ]);
            } else {
                DB::table('carts')->insert([
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'qty' => $request->qty,
                    'total' => intval($request->qty * $request->price),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $session = [
                'message' => 'Berhasil memasukkan produk ke keranjang, ayo checkout sekarang!',
                'type' => 'Keranjang!',
                'alert' => 'Notifikasi Sukses!',
                'class' => 'success'
            ];
            return redirect()->route('home.product', ['product' => $request->product_id])->with($session);
        }
    }

    public function show(Order $order)
    {
        return view('checkout.detail-checkout', [
            'title' => 'DnG Store | Checkout',
            'user' => auth()->user(),
            'order' => $order,
            'menu' => ['Order']
        ]);
    }

    public function checkout(Request $request, Product $product)
    {
        $user = auth()->user();
        $price = $user->role == 4 ? $product->customer_price : $product->reseller_price;

        $order = Order::where([
            ['user_id', '=', "$user->id"],
            ['product_id', '=', "$product->id"],
            ['status', '=', 'Unpaid'],
        ])->get()->first();

        if ($order) {
            DB::table('orders')->where([
                ['user_id', '=', "$user->id"],
                ['product_id', '=', "$product->id"],
                ['status', '=', 'Unpaid'],
            ])->update([
                'qty' => $order->qty + $request->get('qty'),
                'total_price' => $order->total_price + ($request->get('qty') * $price)
            ]);
        } else {
            DB::table('orders')->insert([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'qty' => $request->get('qty'),
                'total_price' => $request->get('qty') * $price,
                'status' => 'Unpaid'
            ]);
        }
        return redirect()->route('order', ['order' => $order->id]);
    }

    public function update(Request $request, Order $order)
    {
        $session = [
            'message' => 'Berhasil mengupdate!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        $request->validate([
            'qty' => 'required'
        ]);
        DB::beginTransaction();
        DB::table('orders')->where('id', $order->id)->update([
            'qty' => $request->qty,
            'total_price' => $request->price,
            'status' => 'Ordered',
            'send_to' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi"
        ]);

        $status = 'Unpaid';
        if ($request->payment_method == 'COD' || $request->payment_method == 'Cash') {
            $status = 'Paid';
        }

        DB::table('invoices')->insert([
            'invoice_code' => 'INV-1003' . Random::generate(3, '0-9') . substr($order->created_at, 0, 4) . $order->id,
            'order_id' => $order->id,
            'grand_total' => $order->total_price,
            'status' => $status,
            'payment_method' => $request->payment_method
        ]);
        DB::commit();
        $last_invoice = DB::table('invoices')->latest('id')->first();
        // DB::beginTransaction();
        // DB::table('orders')->insert([
        //     'user_id' => $order->user->id,
        // ]);
        // DB::commit();
        // DB::raw("
        // BEGIN;
        // INSERT INTO orders (user_id, product_id, qty, total_price, send_to, status, created_at, updated_at) VALUES (4, 3, 10, 10000, \"subang\", NULL, now(), now());
        // INSERT INTO invoices (order_id, invoice_code) VALUES ((SELECT LAST_INSERT_ID()), \"asue\");
        // COMMIT;
        // ");
        if ($last_invoice->status == 'Paid') {
            return redirect()->route('order')->with($session);
        }
        return redirect()->route('invoice.order', ['invoice' => $last_invoice->id])->with($session);
    }

    public function item()
    {
        return view('order-item');
    }

    // public function checkout(Request $request)
    // {
    //     $request->request->add(['total_price' => $request->qty * 10000, 'status' => 'Unpaid']);
    //     $order = Order::create($request->all());
    //     // Set your Merchant Server Key
    //     \Midtrans\Config::$serverKey = 'midtrans.server_key';
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     \Midtrans\Config::$is3ds = true;

    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => $order->id,
    //             'gross_amount' => $order->total_price,
    //         ),
    //         'customer_details' => array(
    //             'first_name' => $request->name,
    //             'last_name' => '',
    //             'address' => $request->address,
    //             'phone' => $request->phone,
    //         ),
    //     );

    //     $snapToken = \Midtrans\Snap::getSnapToken($params);
    //     return view('checkout', compact('snapToken', 'order'));
    // }
    public function callback(Request $request)
    {
        $serverkey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverkey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' or $request->transaction_status == 'settlement') {
                $order = Order::find($request->order_id);
                $order->update(['status' => 'Paid']);
            }
        }
    }

    public function invoice($id)
    {
        $order = Order::find($id);
        return view('invoice', compact('order'));
    }

    public function delete(Order $order)
    {
        switch ($order->status) {
            case 'Recive':
                DB::table('orders')->delete($order->id);
                $session = [
                    'message' => 'Berhasil menghapus riwayat transaksi!',
                    'type' => 'Tambah ke Keranjang',
                    'alert' => 'Notifikasi Sukses!',
                    'class' => 'success'
                ];
                break;
            case 'Delivery':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan yang sedang dikirimkan!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                break;
            case 'Order Confirmed':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan telah dikonfirmasi!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                break;
            case 'Ordered':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan telah dibayar!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi gagal!',
                    'class' => 'danger'
                ];
                break;
        }
        return redirect()->route('order')->with($session);
    }
}
