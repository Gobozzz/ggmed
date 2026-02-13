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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_email');
            $table->string('customer_phone', 40);
            $table->string('customer_name', 100);
            $table->string('customer_city', 50);
            $table->string('customer_street', 80);
            $table->string('customer_house', 20);
            $table->decimal('total_amount', 10);
            $table->string('payment_provider', 50);
            $table->string('payment_status', 50);
            $table->string('comment', 700)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
