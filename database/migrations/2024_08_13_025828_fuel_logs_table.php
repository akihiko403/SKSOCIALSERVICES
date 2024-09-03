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
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('driver_id')->constrained('drivers');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('product');
            $table->float('liters_requested');
            $table->float('actual_purchase_liters');
            $table->float('price_per_liter');
            $table->float('total_amount');
            $table->string('trip');
            $table->string('purpose');
            $table->foreignId('approved_by')->constrained('users');
            $table->text('remarks')->nullable();
            $table->binary('receipt'); // For storing receipt image data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};
