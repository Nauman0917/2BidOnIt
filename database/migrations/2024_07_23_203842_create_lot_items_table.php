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
        Schema::create('lot_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('item_name');
            $table->string('detailed_description');
            $table->float('item_weight');
            $table->string('weight_unit');
            $table->string('item_type');
            $table->string('metal_type');
            $table->string('metal_color');
            $table->string('item_size');
            $table->float('total_gem_weight')->nullable();
            $table->decimal('appraised_value', 15, 2)->nullable();
            $table->decimal('reserve_price', 15, 2)->nullable();
            $table->decimal('startPrice', 15, 2)->nullable();
            $table->decimal('minEstimate', 15, 2)->nullable();
            $table->decimal('maxEstimate', 15, 2)->nullable();
            $table->decimal('buyoutPrice', 15, 2)->nullable();
            $table->decimal('postSalePrice', 15, 2)->nullable();
            $table->string('internalCatalogNumber')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('bar_code_image')->nullable();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_items');
    }
};
