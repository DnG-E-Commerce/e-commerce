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
use Illuminate\Support\Facades\DB;

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

    public function salesReport()
    {
        $user = auth()->user();
        return view('admin.sales-report', [
            'title' => 'DnG Store | Laporan Penjualan',
            'user' => $user,
            'menu' => ['Laporan Penjualan'],
            'orders' => Order::all(),
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
}
