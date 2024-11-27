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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('order_status', ['pending', 'completed', 'cancelled', 'shipped'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->text('shipping_address');
            $table->timestamps();
        });
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
        });
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['pending', 'completed', 'cancelled', 'shipped']);
            $table->morphs('changeable');
            $table->timestamp('changed_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_status_history');
    }
};
