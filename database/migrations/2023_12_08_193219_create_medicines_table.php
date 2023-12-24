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
            //$table->increments('id')->foreign('Favorites_List.Medicines_id');
            $table->id();
            //$table->integer('Classification_id')->index();
            $table->foreignId('Classification_id')->constrained('classifications');
            $table->string('Scientific_name');
            $table->string('Commercial_name');
            $table->string('Manufacturer');
            $table->bigInteger('Available_Quantity');
            $table->string('Expiry_date');
            $table->bigInteger('Price');
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
