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
        $products = Product::select('products.name', DB::raw('SUM(orders.qty) as total_order'))
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->where('orders.status', '!=', null)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_order')
            ->limit(10)
            ->get();
        $arrProduct = [];
        $qty = [];
        foreach ($products as $p => $product) {
            array_push($arrProduct, $product->name);
            array_push($qty, $product->total_order);
        }
        return $this->chart->horizontalBarChart()
            ->setColors(['#FFC107', '#D32F2F'])
            ->addData('Total Penjualan', $qty)
            ->setXAxis($arrProduct);
    }
}
