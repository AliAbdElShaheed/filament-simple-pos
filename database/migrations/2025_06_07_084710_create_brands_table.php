<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('url')->nullable();
            $table->longText('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('primary_hex_color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
