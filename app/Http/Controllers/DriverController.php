<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        $user = auth()->user();
        $invoices = Invoice::where('status', 'Lunas')->get()->all();
        return view('driver.index', [
            'title'    => 'DnG Store | Dashboard Driver',
            'user'     => $user,
            'menu'     => ['Drop Shipping'],
            'invoices' => $invoices
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('driver.profile-driver', [
            'title' => 'Profile Driver',
            'user' => $user,
            'menu' => ['Profile'],
        ]);
    }

    public function invoice(Invoice $invoice)
    {
        $user = auth()->user();
        return view('driver.confirm-recive', [
            'title' => 'DnG Store | Konfirmasi Paket',
            'user' => $user,
            'menu' => ['Drop Shipping', 'Konfirmasi Paket'],
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
        return redirect()->route('driver')->with($session);
    }
}