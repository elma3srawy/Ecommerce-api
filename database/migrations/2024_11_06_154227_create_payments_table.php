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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('payment_method', ['credit_card', 'paypal', 'cash_on_delivery']);
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('stripe_session_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
