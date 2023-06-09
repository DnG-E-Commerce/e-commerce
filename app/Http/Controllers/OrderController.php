<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show(Order $order)
    {
        $user = session('id');
        $order = Order::where('id', '=', "$order->id")->get()->first();

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
        $request->validate([
            'qty' => 'required'
        ]);
        DB::table('orders')->where('id', $order->id)->update([
            'qty' => $request->qty,
            'total_price' => $request->price,
            'status' => 'Paid',
            'payment_method' => $request->payment_method,
            'send_to' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi"
        ]);
        $session = [
            'message' => 'Berhasil mengedit data serta melakukan transaksi!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('order')->with($session);
        //
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
            case 'Paid':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan telah dibayar!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                break;
            case 'Unpaid':
                DB::table('orders')->delete($order->id);
                $session = [
                    'message' => 'Berhasil membatalkan orderan!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi berhasil!',
                    'class' => 'success'
                ];
                break;
        }
        return redirect()->route('order')->with($session);
    }
}
