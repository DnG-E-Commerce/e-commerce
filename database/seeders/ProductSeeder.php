<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Beras Cap Kaki tiga',
            'desc' => 'Beras yang digiling engga pake mesin',
            'customer_price' => 23000,
            'reseller_price' => 20000,
            'photo' => 'image/curved-6.jpg',
            'qty_status' => 'Ready',
            'special_status' => 'Biasa',
            'uom' => 'Kilogram',
            'weight' => 4,
            'qty' => 100,
            'category_id' => 1
        ]);
        DB::table('products')->insert([
            'name' => 'Teh Pucuk Harum',
            'desc' => 'Minuman Teh Kemasan Botol, enak klo diminum dingin',
            'customer_price' => 23000,
            'reseller_price' => 20000,
            'photo' => 'image/curved-6.jpg',
            'qty_status' => 'Ready',
            'special_status' => 'Biasa',
            'uom' => 'Box',
            'weight' => 2,
            'qty' => 100,
            'category_id' => 4
        ]);
        DB::table('products')->insert([
            'name' => 'Chitato',
            'desc' => 'Snack enak pokoknya',
            'customer_price' => 23000,
            'reseller_price' => 20000,
            'photo' => 'image/curved-6.jpg',
            'qty_status' => 'Ready',
            'special_status' => 'Biasa',
            'uom' => 'Box',
            'weight' => 1,
            'qty' => 100,
            'category_id' => 2
        ]);
        DB::table('products')->insert([
            'name' => 'SOOOOOOOO NICEEEE',
            'desc' => 'Sosis So Nice...... Enak tau!!!',
            'customer_price' => 23000,
            'reseller_price' => 20000,
            'photo' => 'image/curved-6.jpg',
            'qty_status' => 'Ready',
            'special_status' => 'Biasa',
            'uom' => 'Box',
            'weight' => 3,
            'qty' => 100,
            'category_id' => 2
        ]);
        DB::table('products')->insert([
            'name' => 'Crab Stick',
            'desc' => 'Frozen food yg enak pokoknya, apalagi klo buat rebusan.',
            'customer_price' => 23000,
            'reseller_price' => 20000,
            'photo' => 'image/curved-6.jpg',
            'qty_status' => 'Ready',
            'special_status' => 'Biasa',
            'uom' => 'Kilogram',
            'weight' => 1,
            'qty' => 100,
            'category_id' => 3
        ]);
    }
}
