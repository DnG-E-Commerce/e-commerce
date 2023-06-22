<?php

namespace App\Http\Controllers;

use App\Charts\CustomersAndResellerChart;
use App\Charts\OrderChart;
use App\Charts\OrdersChart;
use App\Charts\ProductsChart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function salesReport()
    {
        $user = auth()->user();
        return view('owner.sales-report', [
            'title' => 'DnG Store | Laporan Penjualan',
            'user' => $user,
            'menu' => ['Laporan Penjualan'],
            'orders' => Order::all(),
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('owner.profile-owner', [
            'title' => 'Profile Owner',
            'user' => $user,
            'menu' => ['Profile'],
        ]);
    }
}
