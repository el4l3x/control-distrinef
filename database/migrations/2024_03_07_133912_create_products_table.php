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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('gfc');
            $table->string('climahorro');
            $table->string('ahorraclima');
            $table->string('expertclima');
            $table->string('tucalentadoreconomico');
            $table->string('gfc_price')->nullable();
            $table->string('climahorro_price')->nullable();
            $table->string('ahorraclima_price')->nullable();
            $table->string('expertclima_price')->nullable();
            $table->string('tucalentadoreconomico_price')->nullable();
            $table->string('climahorro_percent')->nullable();
            $table->string('ahorraclima_percent')->nullable();
            $table->string('expertclima_percent')->nullable();
            $table->string('tucalentadoreconomico_percent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
