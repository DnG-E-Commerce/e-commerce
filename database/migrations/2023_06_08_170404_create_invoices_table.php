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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('invoice_code');
            $table->double('ongkir')->nullable();
            $table->double('grand_total');
            $table->text('send_to')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas', 'Pending'])->nullable();
            $table->enum('payment_method', ['transfer', 'cash', 'cod'])->nullable();
            $table->integer('is_pickup')->nullable();
            $table->integer('is_recive')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
