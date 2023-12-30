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
        Schema::create('favorite__lists', function (Blueprint $table) {
            $table->id();
            //$table->integer('User_id')->index();
            $table->foreignId('User_id')->constrained('users');
            //$table->integer('Medicines_id')->index();
            $table->foreignId('Medicines_id')->constrained('medicines');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite__lists');
    }
};
