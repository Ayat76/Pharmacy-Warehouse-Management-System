<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->foreign('Orders_Medicines.Orders_id');
            $table->integer('User_id')->index();
            $table->enum('Order_Status',['sent','received','pending']); //edite to enum
            $table->enum('Payment_Status',['paid','unpaid']);
            $table->integer('final_price');
            $table->integer('Price_Medicine');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
