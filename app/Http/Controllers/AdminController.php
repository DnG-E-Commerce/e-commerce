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
use Illuminate\Support\Facades\Hash;
use PDF;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    public function product()
    {
        $user = auth()->user();
        $products = DB::table('products as p')
            ->select('p.id as product_id', 'p.*', 'c.category')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->get()->all();
        return view('product.index', [
            'title' => 'DnG Store | Produk',
            'menu' => ['Produk'],
            'user' => $user,
            'products' => $products,
        ]);
    }

    public function category()
    {
        $user = auth()->user();
        $categories = DB::table('categories')->get()->all();
        return view('category.index', [
            'title' => 'DnG Store | Kategori',
            'menu' => ['Kategori'],
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function admin()
    {
        $user = auth()->user();
        $admin = User::all();
        return view('user.admin.index', [
            'title' => 'DnG Store | Admin',
            'menu' => ['Admin'],
            'user' => $user,
            'admins' => $admin
        ]);
    }

    public function reseller()
    {
        $user = auth()->user();
        $resellers = User::where('role', 'Reseller')->get();
        return view('user.reseller.index', [
            'title' => 'DnG Store | Reseller',
            'menu' => ['Reseller'],
            'user' => $user,
            'resellers' => $resellers
        ]);
    }

    public function customer()
    {
        $user = auth()->user();
        $customers = User::where('role', 'Customer')->get();
        return view('user.customer.index', [
            'title' => 'DnG Store | Customer',
            'menu' => ['Customer'],
            'user' => $user,
            'customers' => $customers,
        ]);
    }

    public function order()
    {
        $user = auth()->user();
        $invoices = Invoice::orderBy('created_at', 'desc')->get();
        return view('admin.invoice.index', [
            'title' => 'DnG Store | Menu Invoice',
            'menu' => ['Pesanan'],
            'status' => ['Diterima', 'Dikirim', 'Dikonfirmasi/Dikemas', 'Dipesan'],
            'user' => $user,
            'invoices' => $invoices,
        ]);
    }

    public function area()
    {
        $user = auth()->user();
        $areas = DB::table('areas')->get()->all();
        return view('area.index', [
            'title' => 'DnG Store | Area Pemesanan',
            'menu'  => ['Area'],
            'user' => $user,
            'areas' => $areas
        ]);
    }

    public function salesGraph(OrdersChart $ordersChart, ProductsChart $productsChart, CustomersAndResellerChart $customersAndResellerChart)
    {
        $user = auth()->user();
        return view('admin.grafik', [
            'title' => 'DnG Store | Grafik Penjualan',
            'menu' => ['Grafik Penjualan'],
            'user' => $user,
            'productChart' => $productsChart->build(),
            'CustomerResellerChart' => $customersAndResellerChart->build(),
        ]);
    }

    public function salesReport(Request $request)
    {   
        $user = auth()->user();
        if ($request->fromDate_transaction) {
            $order = Order::whereBetween('orders.created_at', [$request->fromDate_transaction, $request->toDate_transaction])
                ->get();
        } else {
              
            $order = Order::orderBy('created_at', 'desc')->get()->all();
        }

        if ($request->fromDate_product) {
            $product = Product::select('products.name', DB::raw('SUM(orders.qty) as total_penjualan'))
                ->join('orders', 'products.id', '=', 'orders.product_id')
                ->where('orders.status', '!=', null)
                ->whereBetween('orders.created_at', [$request->fromDate_product, $request->toDate_product])
                ->groupBy('products.id', 'products.name')
                ->orderBy('products.name', 'asc')
                ->get();
        } else {
            $product = Product::select('products.name', DB::raw('SUM(orders.qty) as total_penjualan'))
                ->join('orders', 'products.id', '=', 'orders.product_id')
                ->where('orders.status', '!=', null)
                ->groupBy('products.id', 'products.name')
                ->orderBy('products.name', 'asc')
                ->get();
        }
        return view('admin.sales-report', [
            'title' => 'DnG Store | Laporan Penjualan',
            'user' => $user,
            'menu' => ['Laporan Penjualan'],
            'orders' => $order,
            'products' => $product,
        ]);
    }

    public function delivery()
    {
        $user = auth()->user();
        $invoices = Invoice::where('status', 'Lunas')->orWhere('status', 'Belum Lunas')->orderBy('created_at', 'desc')->get()->all();
        return view('driver.index', [
            'title'    => 'DnG Store | Pengiriman Barang',
            'menu'     => ['List Pengiriman'],
            'user'     => $user,
            'invoices' => $invoices,
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile-admin', [
            'title' => 'DnG Store | My Profile',
            'menu' => ['Profile'],
            'user' => $user,
        ]);
    }

    public function changePassword()
    {
        $user = auth()->user();
        return view('admin.change-password-admin', [
            'title' => 'DnG Store | Ubah Password',
            'menu' => ['Profile', 'Ubah Password'],
            'user' => $user,
        ]);
    }

    public function showInvoice(Invoice $invoice)
    {
        $user = auth()->user();
        return view('admin.invoice.detail-invoice', [
            'title' => 'DnG Store | Admin | Detail Invoice',
            'menu' => ['Pesanan', 'Detail Pesanan'],
            'user' => $user,
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
        return redirect()->route('su.order')->with($session);
    }

    public function edit()
    {
        $user = auth()->user();
        return view('admin.edit-admin', [
            'title' => 'DnG Store | Edit Profile',
            'menu' => ['Profile', 'Edit Profile'],
            'role' => ['Owner', 'Admin', 'Driver', 'Reseller', 'Customer'],
            'user' => $user,
        ]);
    }

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
        return redirect()->route('su.profile')->with($session);
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:4',
            'repeat_password' => 'required|same:password'
        ]);
        if (Hash::check($request->old_password, $user->password)) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->password),
                'updated_at' => now('Asia/Jakarta')
            ]);
            $session = [
                'message' => 'Berhasil mengubah password!',
                'type' => 'Ubah Password',
                'alert' => 'Notifikasi Berhasil!',
                'class' => 'success'
            ];
            return redirect()->route('su.profile')->with($session);
        }
        $session = [
            'message' => 'Gagal Mengubah password, pastikan password lama benar!',
            'type' => 'Ubah Password',
            'alert' => 'Notifikasi Gagal!',
            'class' => 'danger'
        ];
        return redirect()->route('su.profile.change-password')->with($session);
    }
}
