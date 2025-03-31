<?php

use App\Models\Unit;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('sku')->nullable()->unique();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('barcode')->nullable();
            $table->foreignIdFor(Unit::class);
            $table->decimal('price')->default(0);
            $table->decimal('cost')->default(0);
            $table->boolean('has_composition')->default(false);
            $table->boolean('manage_stock')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
