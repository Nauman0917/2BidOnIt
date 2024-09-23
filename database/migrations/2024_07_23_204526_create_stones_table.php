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
        Schema::create('stones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('stone_type');
            $table->float('stone_weight');
            $table->boolean('stone_weight_exact')->default(0);
            $table->string('stone_shape');
            $table->string('stone_color');
            $table->integer('stones_quantity');
            $table->string('stone_clarity');
            $table->boolean('stone_certified')->default(0);
            $table->boolean('certification_number')->nullable();
            $table->boolean('certified_by')->nullable();
            $table->boolean('certification_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stones');
    }
};
