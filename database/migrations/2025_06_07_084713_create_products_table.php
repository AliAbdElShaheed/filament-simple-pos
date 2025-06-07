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
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('sku')->unique();
            $table->longText('description')->nullable();
            $table->string('price')->default('0.00');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('image')->nullable();
            $table->enum('type', ['deliverable', 'downloadable'])->default('deliverable');
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
