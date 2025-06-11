<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('bar_code')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['deliverable', 'downloadable'])->default('deliverable');
            //$table->enum('unit_type', ['قطعة', 'علبة', 'زجاجة', 'كيس', 'قلم', 'عبوة', 'باكو', 'طبق', 'شيكاره', 'كيلو', 'كانز', 'شريطين'])->nullable();


            $table->decimal('purchase_price', 8, 3)->unsigned()->default(0.00);
            $table->decimal('sale_price', 10, 3)->unsigned()->default(10.00);
            $table->decimal('product_profit', 8, 3)->unsigned()->default(10.00);

            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('minimum_quantity')->default(0);


            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
