<?php

namespace App\Charts;

use App\Models\Order;
use App\Models\Product;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class ProductsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\HorizontalBar
    {
        $products = Product::all();
        $arrProduct = [];
        $arrPenjualanProduct = [];
        $qty = [];
        foreach ($products as $p => $product) {
            $order = DB::table('orders')->where('product_id', $product->id)->sum('qty');
            array_push($arrProduct, $product->name);
            array_push($qty, $order);
        }
        return $this->chart->horizontalBarChart()
            ->setColors(['#FFC107', '#D32F2F'])
            ->addData('Total Penjualan', $qty)
            ->setXAxis($arrProduct);
    }
}
