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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->double('customer_price');
            $table->double('reseller_price');
            $table->string('photo');
            $table->string('uom')->nullable();
            $table->float('weight')->nullable();
            $table->integer('qty')->default(0);
            $table->enum('qty_status', ['Ready', 'Habis'])->nullable();
            $table->enum('special_status', ['Pre Order', 'Biasa'])->nullable();
            $table->foreignId('category_id');
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
        Schema::dropIfExists('products');
    }
};
