<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Twilio\Rest\Client;

class InvoiceController extends Controller
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
        $notification = DB::table('notifications')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);
        $invoices = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->get()->all();
        return view('invoice.index', [
            'title' => 'DnG Store | Transaksi',
            'menu' => ['Transaksi'],
            'user' => $user,
            'notifications' => $notification,
            'invoices' => $invoices
        ]);
    }

    public function suDetailInvoice(Invoice $invoice)
    {
        $user = auth()->user();
        return view('admin.invoice.detail-invoice', [
            'title' => 'DnG Store |  Detail Invoice',
            'menu' => ['Pesanan', 'Detail Pesanan'],
            'user' => $user,
            'invoice' => $invoice,
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
            ['bank' => 'cash', 'name' => 'Cash (Bayar Di Toko)'],
            ['bank' => 'cod', 'name' => 'COD (Cash On Delivery)'],
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

    public function update(Request $request, Invoice $invoice)
    {
        $user = auth()->user();

        DB::beginTransaction();
        switch ($request->payment_method) {
            case 'cash':
                $this->handleCash($request->all(), $invoice->id);
                break;

            case 'cod':
                if ($request->kelurahan == 'Pilih') {
                    $session = [
                        'message' => 'Harap lengkapi data alamat!',
                        'type' => 'Proses Gagal!',
                        'alert' => 'Notifikasi gagal!',
                        'class' => 'danger'
                    ];
                    return redirect()->back()->with($session);
                }
                $this->handleCOD($request->all(), $invoice->id);
                break;

            case 'transfer':
                if ($request->is_pickup == 'dikirim' && $request->kelurahan == 'Pilih') {
                    $session = [
                        'message' => 'Harap lengkapi data alamat!',
                        'type' => 'Proses Gagal!',
                        'alert' => 'Notifikasi gagal!',
                        'class' => 'danger'
                    ];
                    return redirect()->back()->with($session);
                }
                $this->handleTransfer($request->all(), $invoice->id);
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
        return redirect()->route('us.invoice.show', ['invoice' => $invoice->id])->with($session);
    }
    
    
    public function handleCash($data, $id)
    {
        $invoice = Invoice::where('id', $id)->first();
        DB::table('invoices')->where('id', $invoice->id)
            ->update([
                'status' => 'Belum Lunas',
                'send_to' => '-',
                'ongkir' => 0,
                'grand_total' => $invoice->grand_total,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ? $data['notes'] : '-',
                'is_pickup' => 1,
                'is_recive' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    public function handleCOD($data, $id)
    {

        $invoice = Invoice::where('id', $id)->first();
        $area = DB::table('areas')
            ->where([
                ['provinsi', '=', $data['provinsi']],
                ['kabupaten', '=', $data['kabupaten']],
                ['kecamatan', '=', $data['kecamatan']],
                ['kelurahan', '=', $data['kelurahan']]
            ])->first();

        $ongkir = $area ? $area->ongkir : 0;

        DB::table('invoices')->where('id', $invoice->id)
            ->update([
                'status' => 'Belum Lunas',
                'send_to' => $data['kelurahan'] . ', ' . $data['kecamatan'] . ', ' . $data['kabupaten'] . ', ' . $data['provinsi'],
                'ongkir' => $ongkir,
                'grand_total' => $invoice->grand_total + $ongkir,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'is_pickup' => 0,
                'is_recive' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        foreach ($invoice->order as $order) {
            $product = Product::where('id', $order->product_id)->first();
            DB::table('products')->where('id', $order->product_id)->update([
                'qty' => $product->qty - $order->qty,
                'qty_status' => $product->qty - $order->qty == 0 ? 'Habis' : $product->qty_status,
            ]);
        }
    }

    public function handleTransfer($data, $id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $area = DB::table('areas')
            ->where([
                ['provinsi', '=', $data['provinsi']],
                ['kabupaten', '=', $data['kabupaten']],
                ['kecamatan', '=', $data['kecamatan']],
                ['kelurahan', '=', $data['kelurahan']]
            ])->first();

        $ongkir = $area ? $area->ongkir : 0;
        $address = $data['kelurahan'] . ', ' . $data['kecamatan'] . ', ' . $data['kabupaten'] . ', ' . $data['provinsi'];
        DB::table('invoices')->where('id', $invoice->id)
            ->update([
                'status' => 'Lunas',
                'send_to' => $data['is_pickup'] == 'diambil' ? '-' : $address,
                'ongkir' => $ongkir,
                'grand_total' => $invoice->grand_total + $ongkir,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'is_pickup' => $data['is_pickup'] == 'diambil' ? 1 : 0,
                'is_recive' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        foreach ($invoice->order as $order) {
            $product = Product::where('id', $order->product_id)->first();
            DB::table('products')->where('id', $order->product_id)->update([
                'qty' => $product->qty - $order->qty,
                'qty_status' => $product->qty - $order->qty == 0 ? 'Habis' : $product->qty_status,
            ]);
        }
        $this->notifWA($invoice->id);
    }

    public function notifWA($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $token = '65a3c12f5cab3d92c213ddf2ae811013372c3852b2dc61d5e6201b885e0c49f8';
        $whatsapp_phone = '+6283138578369';
        $user = User::where('id', $invoice->user_id)->first();

        $message = "Selamat Transaksi $user->name dengan invoice kode " . $invoice->invoice_code . " Berhasil";

        $url = "https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp";

        $data = [
            "phone" => $whatsapp_phone,
            "messageType" => "text",
            "body" => $message
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "API-Key: $token",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        curl_exec($curl);
        curl_close($curl);
    }

    public function sendWhatsapp($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $sid = "ACeeae51f34b58855ae6f4f64439905adc";
        $token = "6964838bdaf692c2998ae794f522ca85";
        $twilioNumber = "+14155238886";
        $recipientNumber = "+6283138578369";
        $user = User::where('id', $invoice->user_id)->first();
        $client = new Client($sid, $token);

        $message = $client->messages->create(
            'whatsapp:' . $recipientNumber, // Replace with the recipient's WhatsApp number
            [
                'from' => 'whatsapp:' . $twilioNumber,
                'body' => "Selamat Transaksi $user->name dengan invoice kode " . $invoice->invoice_code . " Berhasil", // Replace with your desired message
            ]
        );

        return response()->json(['message' => 'WhatsApp message sent successfully.', 'messageSid' => $message->sid]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function confirmRecive(Invoice $invoice)
    {
        $user = auth()->user();
        DB::table('invoices')->where([
            ['id', $invoice->id],
            ['invoice_code', $invoice->invoice_code]
        ])->update([
            'is_recive' => 1,
            'updated_at' => now('Asia/Jakarta'),
        ]);
        DB::table('notifications')->insert([
            'user_id' => $user->id,
            'title' => 'Barang Telah Diterima!',
            'message' => "Pesanan dengan Invoice $invoice->invoice_code telah diterima! terimakasih telah berbelanja di D&G Store",
            'is_read' => 0,
            'created_at' => now('Asia/Jakarta'),
        ]);
        $session = [
            'message' => 'Pesanan Berhasil diterima! Terimakasih telah berbelanja di D&G Store',
            'type' => 'Penerimaan Pesanan',
            'alert' => 'Notifikasi berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('us.invoice.show', ['invoice' => $invoice->id])->with($session);
    }

    public function confirmCash(Invoice $invoice)
    {
        foreach ($invoice->order as $order) {
            DB::table('orders')->where('id', $order->id)->update([
                'status' => 'Diterima'
            ]);
            $product = DB::table('products')->where('id', $order->product_id)->first();
            DB::table('products')->where('id', $order->product_id)->update([
                'qty' => $product->qty - $order->qty,
                'qty_status' => $product->qty - $order->qty == 0 ? 'Habis' : $product->qty_status,
            ]);
        }

        DB::table('invoices')->where('invoice_code', $invoice->invoice_code)->update([
            'status' => 'Lunas',
            'is_recive' => 1,
            'updated_at' => now('Asia/Jakarta')
        ]);

        $session = [
            'message' => 'Berhasil mengupdate status pembayaran invoice',
            'type' => 'Status Pembayaran Invoice',
            'alert' => 'Notifikasi berhasil!',
            'class' => 'success'
        ];
        return redirect()->route('su.invoice.detail', ['invoice' => $invoice->id])->with($session);
    }

    public function print_pdf(Invoice $invoice)
    {
        $html = "
        <!doctype html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Bootstrap demo</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM' crossorigin='anonymous'>
        </head>
        <body>
            <div class='container'>
                <div class='row justify-content-center mt-5'>
                    <h1 class='text-center'>Invoice</h1>
                    <hr class='border border-1 border-dark'>
                    <div class='col-lg-6'>
                        <h4>List Pesanan</h4>
                        <ul class='list-group list-group-flush'>
                            ";
        foreach ($invoice->order as $o => $order) {
            $html .= "<li class='list-group-item'>" . $order->product->name . " ($order->qty) (Rp. $order->total) </li>";
        }
        $html .= "
                        </ul>
                    </div>
                    <hr class='border border-1 border-dark'>
                    <div class='col-lg-6'>
                        <table class='table'>
                            <tr>
                                <th>No Invoice</th>
                                <td>:</td>
                                <td>$invoice->invoice_code</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>:</td>
                                <td>" . substr($invoice->created_at, 0, 10) . "</td>
                            </tr>
                            <tr>
                                <th>Nama Pemesan</th>
                                <td>:</td>
                                <td>";
        foreach ($invoice->order as $key => $order) {
            $html .= $order->user->name;
            break;
        }
        $html .= "</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>";
        $invoice->send_to ? $html .= $invoice->send_to : $html .= "-";
        $html .= "</td>
                            </tr>
                            <tr>
                                <th>Ongkir</th>
                                <td>:</td>
                                <td>Rp. " . number_format($invoice->ongkir, 0, ',', '.') . "</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>:</td>
                                <td>Rp. " . number_format($invoice->grand_total, 0, ',', '.') . "</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>:</td>
                                <td>$invoice->status</td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>:</td>
                                <td>$invoice->payment_method</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz' crossorigin='anonymous'></script>
        </body>
        </html>
        ";
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }

    public function delete(Invoice $invoice)
    {
        if (in_array($invoice->status, ['Pending', 'Belum Lunas'])) {
            foreach ($invoice->order as $key => $order) {
                if ($order->status != 'Dipesan') {
                    $session = [
                        'message' => "Anda tidak dapat membatalkan pesanan yang telah $order->status!",
                        'type' => 'Menghapus Orderan',
                        'alert' => 'Notifikasi gagal!',
                        'class' => 'danger'
                    ];
                    return redirect()->route('us.invoice')->with($session);
                }
                if (in_array($invoice->payment_method, ['cod', 'transfer'])) {
                    $product = Product::where('id', $order->product_id)->first();
                    DB::table('products')->where('id', $product->id)
                        ->update([
                            'qty' => $product->qty + $order->qty
                        ]);
                }
                DB::table('orders')->delete($order->id);
            }
            DB::table('invoices')->delete($invoice->id);
            $session = [
                'message' => 'Berhasil membatalkan orderan!',
                'type' => 'Menghapus Orderan',
                'alert' => 'Notifikasi berhasil!',
                'class' => 'success'
            ];
            return redirect()->route('us.order')->with($session);
        } else {
            $session = [
                'message' => 'Anda tidak dapat membatalkan pesanan yang sudah lunas dan dikonfirmasi oleh admin!',
                'type' => 'Menghapus Orderan',
                'alert' => 'Notifikasi gagal!',
                'class' => 'danger'
            ];
            return redirect()->route('us.invoice')->with($session);
        }
    }
}
