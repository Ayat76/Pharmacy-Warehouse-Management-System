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
        Schema::create('medicines', function (Blueprint $table) {
            $table->increments('id')->foreign('Favorites_List.Medicines_id');
            $table->integer('Classification_id')->index();
            $table->string('Scientific_name');
            $table->string('Commercial_name');
            $table->string('Manufacturer');
            $table->integer('Available_Quantity');
            $table->integer('Expiry_date');
            $table->integer('Price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
