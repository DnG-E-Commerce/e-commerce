<?php

namespace App\Charts;

use App\Models\Order;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class OrdersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        foreach ($month as $key => $m) {
            $order = Order::whereMonth('created_at', $key + 1)->count();
            // $order = DB::table('orders')->whereMonth('created_at', '=', $key + 1)->get()->all();
            array_push($data, $order);
        }
        return $this->chart->lineChart()
            ->addData('Penjualan', $data)
            ->setXAxis($month);
    }
}
