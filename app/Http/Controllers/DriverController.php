<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function invoice(Invoice $invoice)
    {
        $user = auth()->user();
        return view('driver.confirm-recive', [
            'title' => 'DnG Store | List Pengiriman',
            'user' => $user,
            'menu' => ['List Pengiriman', 'Upload Bukti Pengiriman'],
            'invoice' => $invoice
        ]);
    }

    public function store(Request $request, Invoice $invoice)
    {
        $user = auth()->user();
        $request->validate([
            'photo' => 'required|image|max:8149',
        ]);
        $photo = $request->file('photo')->store('confirmation');
        DB::table('invoices')->where('id', $invoice->id)
            ->update([
                'status' => 'Lunas'
            ]);
        DB::table('orders')->where('invoice_id', $invoice->id)
            ->update([
                'status' => 'Diterima'
            ]);
        DB::table('shippings')->insert([
            'user_id' => $user->id,
            'invoice_id' => $invoice->id,
            'photo' => $photo,
            'created_at' => now('Asia/Jakarta'),
        ]);
        $session = [
            'message' => 'Berhasil mengupdate status pesanan!',
            'type' => 'Update Status Pesanan',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('su.delivery')->with($session);
    }
}
