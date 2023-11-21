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
        Schema::create('orders_medicines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Orders_id')->index();
            $table->integer('Medicines_id')->index();
            $table->integer('Required_quantity');
            $table->integer('quantity_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_medicines');
    }
};
