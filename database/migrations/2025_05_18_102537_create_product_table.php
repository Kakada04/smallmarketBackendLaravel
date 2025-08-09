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
    $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    $table->string('product_name');
    $table->integer('quantity');
    $table->decimal('cost_price', 10, 2);
    $table->decimal('sell_price', 10, 2);
    $table->text('banner_img');
    $table->text('description');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
