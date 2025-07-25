<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('name');
            $table->integer('price_out')->nullable();
            $table->integer('price_in')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->integer('discount_direct')->nullable();
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            // Thêm cột thông số dạng JSON
            $table->json('details')->nullable(); // Cột JSON cho thông số
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('brand_id')->references('id')->on('brands');
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
