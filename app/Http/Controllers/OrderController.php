<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
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
        $orders = Order::where('user_id', $user->id)->get()->all();
        $invoices = Invoice::where('user_id', $user->id)->get()->all();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('checkout.index', [
            'title' => 'DnG Store | Checkout',
            'user' => $user,
            'orders' => $orders,
            'invoices' => $invoices,
            'menu' => ['Order'],
            'notifications' => $notification
        ]);
    }

    public function storeToOrder(Request $request)
    {
        $request->validate([
            'qty' => 'required|numeric'
        ]);

        // Check QTY
        $product = Product::where('id', $request->product_id)->first();
        // dd($request->qty > $product->qty);
        if ($request->qty > $product->qty) {
            $session = [
                'message' => 'Anda tidak dapat memesan barang melebihi kuantitas yang tersedia!',
                'type' => 'Checkout!',
                'alert' => 'Notifikasi Gagal!',
                'class' => 'danger'
            ];
            return redirect()->route('us.product.detail', ['product' => $request->product_id])->with($session);
        }

        $user = auth()->user();
        DB::beginTransaction();
        DB::table('invoices')->insert([
            'user_id' => $user->id,
            'invoice_code' => 'INV-1003' . Random::generate(4, '0-9') . date('Y') * date('m') * date('d'),
            'grand_total' => 0,
            'status' => 'Pending',
            'is_pickup' => 0,
            'created_at' => now('Asia/Jakarta')
        ]);
        $last_invoice = DB::table('invoices')->latest('id')->first();
        DB::table('orders')->insert([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'invoice_id' => $last_invoice->id,
            'qty' => $request->qty,
            'total' => intval($request->qty * $request->price),
            'status' => 'Dipesan',
            'created_at' => now('Asia/Jakarta'),
        ]);
        DB::table('invoices')->where('invoice_code', $last_invoice->invoice_code)->update([
            'grand_total' => intval($request->qty * $request->price),
            'updated_at' => now('Asia/Jakarta'),
        ]);
        DB::commit();
        $session = [
            'message' => 'Harap lengkapi data-data untuk melanjutkan transaksi!',
            'type' => 'Checkout!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('us.invoice.edit', ['invoice' => $last_invoice->id])->with($session);
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

        // Check QTY
        $product = Product::where('id', $request->product_id)->first();
        // dd($request->qty > $product->qty);
        if ($request->qty > $product->qty) {
            $session = [
                'message' => 'Anda tidak dapat memesan barang melebihi kuantitas yang tersedia!',
                'type' => 'Checkout!',
                'alert' => 'Notifikasi Gagal!',
                'class' => 'danger'
            ];
            return redirect()->route('us.product.detail', ['product' => $request->product_id])->with($session);
        }

        DB::commit();
        $session = [
            'message' => 'Berhasil memasukkan produk ke keranjang, ayo checkout sekarang!',
            'type' => 'Keranjang!',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('us.product.detail', ['product' => $request->product_id])->with($session);
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
        return redirect()->route('us.invoice.edit', ['invoice' => $last_invoice->id])->with($session);
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
        return redirect()->route('us.invoice')->with($session);
    }

    public function suUpdateStatus(Request $request, Invoice $invoice)
    {
        DB::table('orders')->where('invoice_id', $invoice->id)
            ->update([
                'status' => $request->status
            ]);
        if ($request->status == 'Dikonfirmasi/Dikemas') {
            DB::table('notifications')->insert([
                'user_id' => $invoice->user_id,
                'title' => 'Pesanan Dikonfirmasi/Dikemas',
                'message' => "Pesanan anda telah dikonfirmasi oleh admin! dan pesanan anda sedang disiapkan",
                'is_read' => 0,
                'created_at' => now('Asia/Jakarta'),
            ]);
        } else if ($request->status == 'Dikirim') {
            DB::table('notifications')->insert([
                'user_id' => $invoice->user_id,
                'title' => 'Pesanan telah dikirim',
                'message' => "Pesanan anda telah dikirim oleh admin! harap menunggu dengan sabar",
                'is_read' => 0,
                'created_at' => now('Asia/Jakarta'),
            ]);
        }
        
        // switch ($request->status) {
        //     case 'Dikonfimasi/Dikemas':
        //         break;
        //     case 'Dikirim':
        //         break;
        // }
        $session = [
            'message' => 'Berhasil mengupdate status pesanan!',
            'type' => 'Update Status Pesanan',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.invoice.detail', ['invoice' => $invoice->id])->with($session);
    }
}
