<?php

namespace App\Http\Controllers;

use App\Charts\CustomersAndResellerChart;
use App\Charts\OrderChart;
use App\Charts\OrdersChart;
use App\Charts\ProductsChart;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PDF;

class AdminController extends Controller
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
    public function index(OrdersChart $ordersChart, ProductsChart $productsChart, CustomersAndResellerChart $customersAndResellerChart)
    {
        return view('admin.index', [
            'title' => 'DnG Store | Dashboard',
            'menu' => ['Dashboard'],
            'user' => auth()->user(),
            'users' => User::all(),
            'orders' => Order::all(),
            'products' => Product::all(),
            'orderChart' => $ordersChart->build(),
            'productChart' => $productsChart->build(),
            'CustomerResellerChart' => $customersAndResellerChart->build(),
        ]);
    }

    public function grafik(OrdersChart $ordersChart, ProductsChart $productsChart, CustomersAndResellerChart $customersAndResellerChart)
    {
        return view('admin.grafik', [
            'title' => 'DnG Store | Grafik',
            'menu' => ['Grafik Penjualan'],
            'user' => auth()->user(),

            'orderChart' => $ordersChart->build(),
            'productChart' => $productsChart->build(),
            'CustomerResellerChart' => $customersAndResellerChart->build(),
        ]);
    }

    public function salesReport(Request $request)
    {
        $user = auth()->user();
        if ($request->fromDate_transaction) {
            $order = Order::whereBetween('created_at', [$request->fromDate_transaction, $request->toDate_transaction])->get();
        } else {
            $order = Order::all();
        }

        if ($request->fromDate_product) {
            $product = Product::select('products.name', DB::raw('SUM(orders.qty) as total_penjualan'))
                ->join('orders', 'products.id', '=', 'orders.product_id')
                ->where('orders.status', '!=', null)
                ->whereBetween('orders.created_at', [$request->fromDate_product, $request->toDate_product])
                ->groupBy('products.id', 'products.name')
                ->get();
        } else {
            $product = Product::select('products.name', DB::raw('SUM(orders.qty) as total_penjualan'))
                ->join('orders', 'products.id', '=', 'orders.product_id')
                ->where('orders.status', '!=', null)
                ->groupBy('products.id', 'products.name')
                ->get();;
        }
        return view('admin.sales-report', [
            'title' => 'DnG Store | Laporan Penjualan',
            'user' => $user,
            'menu' => ['Laporan Penjualan'],
            'orders' => $order,
            'products' => $product,
        ]);
    }

    public function invoice()
    {
        $invoices = Invoice::all();
        return view('admin.invoice.index', [
            'title' => 'DnG Store | Menu Invoice',
            'menu' => ['Pesanan'],
            'user' => auth()->user(),
            'invoices' => $invoices,
            'status' => ['Diterima', 'Dikirim', 'Dikonfirmasi/Dikemas', 'Dipesan'],
        ]);
    }

    public function showInvoice(Invoice $invoice)
    {
        return view('admin.invoice.detail-invoice', [
            'title' => 'DnG Store | Admin | Detail Invoice',
            'menu' => ['Pesanan', 'Detail Pesanan'],
            'user' => auth()->user(),
            'invoice' => $invoice,
            'status' => ['Diterima', 'Dikirim', 'Dikonfirmasi/Dikemas', 'Dipesan'],
        ]);
    }

    public function orderUpdate(Request $request, Order $order)
    {
        $session = [
            'message' => 'Berhasil mengupdate Pesanan!',
            'type' => 'Edit Pesanan',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];

        $product = DB::table('products')->where('id', $order->product_id)->first();

        if ($order->status == 'Dipesan') {
            DB::table('orders')->where('id', $order->id)->update([
                'status' => $request->status
            ]);
            DB::table('products')->where('id', $order->product_id)->update([
                'qty' => intval($product->qty - $order->qty)
            ]);
        } else {
            DB::table('orders')->where('id', $order->id)->update([
                'status' => $request->status
            ]);
        }
        return redirect()->route('admin.orders')->with($session);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('admin.profile-admin', [
            'title' => 'DnG Store | My Profile',
            'user' => auth()->user(),
            'menu' => ['Profile'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('admin.edit-admin', [
            'title' => 'DnG Store | Edit Profile',
            'user' => auth()->user(),
            'menu' => ['Profile', 'Edit Profile'],
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customer']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
            'photo' => 'image|max:8192',
            'address' => 'required'
        ]);
        $photo = $request->file('photo');
        if ($photo) {
            $filename = $photo->store('image');
        } else {
            $filename = $user->photo;
        }
        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $filename,
            'address' => $request->address
        ]);
        $session = [
            'message' => 'Berhasil mengupdate Profile!',
            'type' => 'Edit Profile',
            'alert' => 'Notifikasi Sukses!',
            'class' => 'success'
        ];
        return redirect()->route('admin.profile', ['user' => $user->id])->with($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            $html .= "<li class='list-group-item'>" . $order->product->name . " ($order->qty) </li>";
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
        }
        $html .= "</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>$invoice->send_to</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>$invoice->notes</td>
                            </tr>
                            <tr>
                                <th>Ongkir</th>
                                <td>:</td>
                                <td>" . number_format($invoice->ongkir, 0, ',', '.') . "</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>:</td>
                                <td>" . number_format($invoice->grand_total, 0, ',', '.') . "</td>
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
}
