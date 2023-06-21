<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use PhpParser\Node\Expr\Cast\Array_;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        $invoices = Invoice::where('user_id', $user->id)->get()->all();
        return view('invoice.index', [
            'title' => 'DnG Store | Transaksi',
            'menu' => ['Transaksi'],
            'user' => $user,
            'notifications' => $notification,
            'invoices' => $invoices
        ]);
    }

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
        $user = auth()->user();
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('invoice.detail-invoice', [
            'title' => 'DnG Store | Detail Invoice',
            'menu' => ['Transaksi', 'Detail Invoice'],
            'user' => $user,
            'notifications' => $notification,
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $user = auth()->user();
        $payment_method = [
            ['bank' => 'transfer', 'name' => 'Transfer'],
            ['bank' => 'cash', 'name' => 'Cash'],
            ['bank' => 'cod', 'name' => 'Cash On Delivery'],
        ];
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        return view('invoice.edit-invoice', [
            'title' => 'DnG Store | Lengkapi Data Pembayaran',
            'user' => auth()->user(),
            'menu' => ['Invoice', 'Lengkapi Data'],
            'invoice' => $invoice,
            'payment_method' => $payment_method,
            'notifications' => $notification
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
        $area = DB::table('areas')
            ->where([
                ['provinsi', '=', $request->provinsi],
                ['kabupaten', '=', $request->kabupaten],
                ['kecamatan', '=', $request->kecamatan],
                ['kelurahan', '=', $request->kelurahan]
            ])->first();

        $ongkir = $area ? $area->ongkir : 0;
        $params = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => md5($invoice->id . Random::generate(2, '0-9')),
                'gross_amount' => $invoice->grand_total + $ongkir,
            ],
            'customer_details' => [
                'first_name' => $invoice->user->name,
                'last_name' => '',
                'address' => $invoice->user->address,
                'phone' => $invoice->user->phone,
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
        $user = auth()->user();
        DB::beginTransaction();
        switch ($request->payment_method) {
            case 'cash':
                $this->handleCash($request->all(), $invoice);
                break;

            case 'cod':
                $this->handleCOD($request->all(), $invoice);
                break;

            case 'transfer':
                $this->handleTransfer($request->all(), $invoice);
                break;
        }
        foreach ($invoice->order as $order) {
            DB::table('orders')->where('id', $order->id)
                ->update([
                    'status' => 'Dipesan',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        DB::table('notifications')->insert([
            'user_id' =>    $user->id,
            'title' => 'Pembayaran Berhasil',
            'message' => "Berhasil melakukan pembayaran untuk invoice $invoice->invoice_code, harap menunggu pesanan dikonfirmasi oleh admin dan melakukan pengecekan pesanan secara berkala",
            'is_read' => 0,
            'created_at' => now('Asia/Jakarta'),
        ]);
        DB::commit();
        $session = [
            'message' => 'Berhasil menyelesaikan transaksi! Terimakasih telah berbelanja di DnG Store',
            'type' => 'Transaksi Berhasil',
            'alert' => 'Notifikasi berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('invoice.show', ['invoice' => $invoice->id])->with($session);
    }

    public function handleCash($data, $invoice)
    {
        DB::table('invoices')->where('id', $invoice['id'])
            ->update([
                'status' => 'Belum Lunas',
                // 'send_to' => $data['kelurahan'] . ', ' . $data['kecamatan'] . ', ' . $data['kabupaten'] . ', ' . $data['provinsi'],
                'ongkir' => 0,
                'grand_total' => $invoice['grand_total'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function handleCOD($data, $invoice)
    {
        $area = DB::table('areas')
            ->where([
                ['provinsi', '=', $data['provinsi']],
                ['kabupaten', '=', $data['kabupaten']],
                ['kecamatan', '=', $data['kecamatan']],
                ['kelurahan', '=', $data['kelurahan']]
            ])->first();

        $ongkir = $area ? $area->ongkir : 0;

        DB::table('invoices')->where('id', $invoice['id'])
            ->update([
                'status' => 'Belum Lunas',
                'send_to' => $data['kelurahan'] . ', ' . $data['kecamatan'] . ', ' . $data['kabupaten'] . ', ' . $data['provinsi'],
                'ongkir' => $ongkir,
                'grand_total' => $invoice['grand_total'] + $ongkir,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function handleTransfer($data, $invoice)
    {
        $area = DB::table('areas')
            ->where([
                ['provinsi', '=', $data['provinsi']],
                ['kabupaten', '=', $data['kabupaten']],
                ['kecamatan', '=', $data['kecamatan']],
                ['kelurahan', '=', $data['kelurahan']]
            ])->first();

        $ongkir = $area ? $area->ongkir : 0;
        DB::table('invoices')->where('id', $invoice['id'])
            ->update([
                'status' => 'Lunas',
                'send_to' => $data['kelurahan'] . ', ' . $data['kecamatan'] . ', ' . $data['kabupaten'] . ', ' . $data['provinsi'],
                'ongkir' => $ongkir,
                'grand_total' => $invoice['grand_total'] + $ongkir,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
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
