<?php

namespace App\Http\Controllers;

use App\Charts\CustomersAndResellerChart;
use App\Charts\OrdersChart;
use App\Charts\ProductsChart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index(OrdersChart $ordersChart, ProductsChart $productsChart, CustomersAndResellerChart $customersAndResellerChart)
    {
        $user = auth()->user();
        return view('owner.index', [
            'title' => 'DnG Store | Dashboard Owner',
            'user'  => $user,
            'menu'  => ['Dashboard'],
            'users' => User::all(),
            'orders' => Order::all(),
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
            $product = Order::whereBetween('created_at', [$request->fromDate_product, $request->toDate_product])->get();
        } else {
            $product = Order::all();
        }
        return view('owner.sales-report', [
            'title' => 'DnG Store | Laporan Penjualan',
            'user' => $user,
            'menu' => ['Laporan Penjualan'],
            'orders' => $order,
            'products' => $product
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile-admin', [
            'title' => 'Profile Owner',
            'user' => $user,
            'menu' => ['Profile'],
        ]);
    }
}
