<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('status')->default('pending');
            //$table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->decimal('shipping_price', 10, 2)->default(0.00);
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->dateTime('placed_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
