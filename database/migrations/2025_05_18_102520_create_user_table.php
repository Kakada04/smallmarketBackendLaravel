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
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('first_name');
    $table->string('last_name');
    $table->string('gender');
    $table->string('gmail')->unique();
    $table->string('password');
    $table->string('phone_number');
    $table->boolean('is_admin')->default(false);
    $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
    $table->timestamp('reg_date')->useCurrent();
    $table->rememberToken();
    $table->timestamps();
});
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
          Schema::dropIfExists('password_reset_tokens');
    }
};
