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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('session_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('cart_items');
    }
};
