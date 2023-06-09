<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->double('total_price');
            $table->text('send_to')->nullable();
            $table->enum('payment_method', ['VA', 'Dana', 'Gopay', 'COD']);
            $table->enum('status', ['Recive', 'Delivery', 'Paid', 'Unpaid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
