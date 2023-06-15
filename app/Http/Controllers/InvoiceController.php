<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice(Invoice $invoice)
    {
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'Oktober', 'November', 'Desember'];
        return view('invoice/detail-invoice', [
            'title' => 'DnG Store | Detail Invoice',
            'user' => auth()->user(),
            'invoice' => $invoice,
            'menu' => ['Invoice'],
            'month' => $month
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $payment_method = [
            ['bank' => 'bri', 'name' => 'BRI Virtual Account'],
            ['bank' => 'bca', 'name' => 'BCA Virtual Account'],
            ['bank' => 'bni', 'name' => 'BNI Virtual Account'],
            ['bank' => 'cash', 'name' => 'Cash'],
            ['bank' => 'cod', 'name' => 'Cash On Delivery'],
        ];
        return view('invoice.edit-invoice', [
            'title' => 'DnG Store | Lengkapi Data Pembayaran',
            'user' => auth()->user(),
            'menu' => ['Invoice', 'Lengkapi Data'],
            'invoice' => $invoice,
            'payment_method' => $payment_method
        ]);
    }

    public function checkout(Request $request, Invoice $invoice)
    {
        $user = auth()->user();
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        // dd($request->payment_method);

        $params = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => md5($invoice->id . Random::generate(2, '0-9')),
                'gross_amount' => $invoice->grand_total,
            ],
            'customer_details' => [
                'first_name' => $invoice->user->name,
                'last_name' => '',
                'address' => $invoice->user->address,
                'phone' => $invoice->user->phone,
            ],
            'bank_transfer' => [
                'bank' => $request->payment_method,
                'va_number' => 11111
            ],
        ];
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json($snapToken);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        DB::beginTransaction();
        if ($request->payment_method == 'cash' || $request->payment_method == 'cod') {
            DB::table('invoices')->where('id', $invoice->id)
                ->update([
                    'status' => 'Pending',
                    'send_to' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi",
                    'payment_method' => $request->payment_method,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            DB::table('invoices')->where('id', $invoice->id)
                ->update([
                    'status' => 'Lunas',
                    'send_to' => "$request->kelurahan, $request->kecamatan, $request->kabupaten, $request->provinsi",
                    'payment_method' => $request->payment_method,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        foreach ($invoice->order as $order) {
            DB::table('orders')->where('id', $order->id)
                ->update([
                    'status' => 'Dipesan',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        DB::commit();
        $session = [
            'message' => 'Berhasil menyelesaikan transaksi! Terimakasih telah berbelanja di DnG Store',
            'type' => 'Transaksi Berhasil',
            'alert' => 'Notifikasi berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('home')->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
