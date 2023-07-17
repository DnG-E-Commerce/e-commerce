<?php

namespace App\Charts;

use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class CustomersAndResellerChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $users = User::select('users.name', DB::raw('SUM(orders.qty) as total_order'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->where('role', '=', 'Customer')
            ->orWhere('role', '=', 'Reseller')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_order')
            ->limit(10)
            ->get();
            
        $XAxis = [];
        $data = [];
        foreach ($users as $u => $user) {
            array_push($XAxis, $user->name);
            array_push($data, intval($user->total_order));
        }
        return $this->chart->pieChart()
            ->addData($data)
            ->setLabels($XAxis)
            ->setXAxis($XAxis);
    }
}
