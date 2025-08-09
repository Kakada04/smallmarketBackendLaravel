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
        Schema::create('data_analysts', function (Blueprint $table) {
    $table->id();
    $table->date('report_date')->unique();
    $table->integer('total_id')->nullable();
    $table->decimal('total_revenue', 10, 2)->nullable();
    $table->integer('total_orders')->nullable();
    $table->integer('products_sold')->nullable();
    $table->decimal('average_order_value', 10, 2)->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_analyst');
    }
};
