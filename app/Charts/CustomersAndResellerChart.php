<?php

namespace App\Charts;

use App\Models\Order;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class CustomersAndResellerChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $users = User::where('role', '=', 'Customer')->orWhere('role', '=', 'Reseller')->get()->all();
        $XAxis = [];
        $data = [];
        foreach ($users as $u => $user) {
            $order = Order::where('user_id', $user->id)->count();
            array_push($XAxis, $user->name);
            array_push($data, $order);
        }
        return $this->chart->pieChart()
            ->addData($data)
            ->setLabels($XAxis)
            ->setXAxis($XAxis);
    }
}
