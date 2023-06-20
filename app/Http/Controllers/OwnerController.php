<?php

namespace App\Http\Controllers;

use App\Charts\OrderChart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        foreach ($month as $key => $m) {
            $order = DB::table('orders')->whereMonth('created_at', '=', $key + 1)->get()->all();
            array_push($data, [count($order)]);
        }
        $orderChart = new OrderChart();
        $orderChart->labels($month);
        $orderChart->dataset('Order by trimister', 'line', $data);
        return view('owner.index', [
            'title' => 'DnG Store | Dashboard Owner',
            'user'  => $user,
            'menu'  => ['Dashboard'],
            'users' => User::all(),
            'orders' => Order::all(),
            'orderChart' => $orderChart
        ]);
    }

    public function salesReport()
    {
        //
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
