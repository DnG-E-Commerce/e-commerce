<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Transaction;
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
            ['status', '=', null]
        ])->get()->all();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('checkout.index', [
            'title' => 'DnG Store | Checkout',
            'user' => $user,
            'orders' => $orders,
            'menu' => ['Order'],
            'notifications' => $notification
        ]);
    }

    public function storeToOrder(Request $request)
    {
        $request->validate([
            'qty' => 'required|numeric'
        ]);
        $user = auth()->user();
        $order = DB::table('orders')->where([
            ['user_id', '=', $user->id],
            ['product_id', '=', $request->product_id],
            ['status', '=', null]
        ])->first();
        DB::beginTransaction();
        if ($order) {
            DB::table('orders')->where([
                ['user_id', '=', $user->id],
                ['product_id', '=', $request->product_id],
                ['status', '=', null]
            ])->update([
                'qty' => $order->qty + $request->qty,
                'total' => $order->total + ($request->qty * $request->price),
                'updated_at' => now('Asia/Jakarta')
            ]);
        } else {
            DB::table('orders')->insert([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'total' => intval($request->qty * $request->price),
                'created_at' => now('Asia/Jakarta'),
            ]);
        }
        DB::commit();
        $session = [
            'message' => 'Harap lengkapi data-data untuk melanjutkan transaksi!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('order')->with($session);
    }

    public function storeToCart(Request $request)
    {
        //
        $request->validate([
            'qty' => 'required|numeric'
        ]);
        $user = auth()->user();
        $cart = DB::table('carts')->where([
            ['user_id', '=', $user->id],
            ['product_id', '=', $request->product_id]
        ])->first();
        DB::beginTransaction();
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
        DB::commit();
        $session = [
            'message' => 'Berhasil memasukkan produk ke keranjang, ayo checkout sekarang!',
            'type' => 'Keranjang!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('home.product', ['product' => $request->product_id])->with($session);
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

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'order' => 'required'
        ]);
        $session = [
            'message' => 'Harap Lengkapi data terlebih dahulu sebelum melanjutkan pembayaran!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        DB::beginTransaction();
        // Generate new invoice
        DB::table('invoices')->insert([
            'user_id' => $user->id,
            'invoice_code' => 'INV-1003' . Random::generate(4, '0-9') . date('Y') * date('m') * date('d'),
            'grand_total' => 0,
            'status' => 'Pending',
            'created_at' => now('Asia/Jakarta')
        ]);
        $total = 0;
        $last_invoice = DB::table('invoices')->latest('id')->first();
        foreach ($request->order as $key => $data) {
            $orderId = $request->order_id[$key];
            $total += $request->total[$key];
            DB::table('orders')->where('id', $orderId)->update([
                'invoice_id' => $last_invoice->id,
                'qty' => $request->qty[$key],
                'total' => $request->total[$key],
                'status' => 'Dipesan',
                'created_at' => now('Asia/Jakarta')
            ]);
        }

        DB::table('invoices')->where('invoice_code', $last_invoice->invoice_code)->update([
            'grand_total' => $total,
            'updated_at' => now('Asia/Jakarta'),
        ]);
        DB::table('notifications')->insert([
            'user_id' => $user->id,
            'title' => 'Melakukan Checkout',
            'message' => "Berhasil men-checkout barang dengan invoice $last_invoice->invoice_code, harap lanjutkan untuk melakukan pembayaran!",
            'is_read' => 0,
            'created_at' => now('Asia/Jakarta')
        ]);
        DB::commit();
        return redirect()->route('invoice.edit', ['invoice' => $last_invoice->id])->with($session);
    }

    public function delete(Order $order)
    {
        switch ($order->status) {
            case 'Diterima':
                DB::table('orders')->delete($order->id);
                $session = [
                    'message' => 'Berhasil menghapus riwayat transaksi!',
                    'type' => 'Tambah ke Keranjang',
                    'alert' => 'Notifikasi Sukses!',
                    'class' => 'success'
                ];
                break;
            case 'Dikirim':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan yang sedang dikirimkan!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                break;
            case 'Dikonfimasi/Dikemas':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan telah dikonfirmasi!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Gagal!',
                    'class' => 'danger'
                ];
                break;
            case 'Dipesan':
                $session = [
                    'message' => 'Tidak dapat menghapus transaksi orderan telah dibayar!',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi gagal!',
                    'class' => 'danger'
                ];
                break;
            default:
                DB::table('orders')->delete($order->id);
                $session = [
                    'message' => 'Berhasil membatalkan data pesanan',
                    'type' => 'Hapus Orderan',
                    'alert' => 'Notifikasi Berhasil!',
                    'class' => 'success'
                ];
        }
        return redirect()->route('order')->with($session);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        DB::table('orders')->where('invoice_id', $invoice->id)
            ->update([
                'status' => $request->status
            ]);
        if ($request->status == 'Dikonfirmasi/Dikemas') {
            foreach ($invoice->order as $key => $order) {
                $product = DB::table('products')->where('id', $order->product_id)->first();
                DB::table('products')->where('id', $order->product_id)
                    ->update([
                        'qty'        =>    $product->qty - $order->qty,
                        'qty_status' =>  $product->qty - $order->qty == 0 ? 'Habis' : 'Ready'
                    ]);
            }
        }
        $session = [
            'message' => 'Berhasil mengupdate status pesanan!',
            'type' => 'Update Status Pesanan',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('admin.detail.invoice', ['invoice' => $invoice->id])->with($session);
    }
}
