<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Owner D&G Store',
            'email' => 'dngstore.owner@gmail.com',
            'role' => 'Owner',
            'password' => Hash::make('owner123'),
            'email_verified_at' => now('Asia/Jakarta')
        ]);
        DB::table('users')->insert([
            'name' => 'Admin D&G Store',
            'email' => 'dngstore.admin@gmail.com',
            'role' => 'Admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now('Asia/Jakarta')
        ]);
        DB::table('users')->insert([
            'name' => 'Driver D&G Store',
            'email' => 'dngstore.driver@gmail.com',
            'role' => 'Driver',
            'password' => Hash::make('driver123'),
            'email_verified_at' => now('Asia/Jakarta'),
        ]);
    }
}
