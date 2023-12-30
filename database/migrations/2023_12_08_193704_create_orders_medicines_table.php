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
            //$table->increments('id');
            $table->id();
            //$table->integer('Orders_id')->index();
            $table->foreignId('Orders_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            //$table->integer('Medicines_id')->index();
            $table->foreignId('Medicines_id')->constrained('medicines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('Required_quantity');
            $table->bigInteger('quantity_price');
            $table->bigInteger('Price_Medicine');
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
