<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('location_id')->constrained('locations')->restrictOnDelete();
            $table->string('sku', 50)->unique();
            $table->string('name', 150);
            $table->string('unit', 30);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->string('condition', 50)->default('baik');
            $table->timestamps();

            $table->index(['category_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
