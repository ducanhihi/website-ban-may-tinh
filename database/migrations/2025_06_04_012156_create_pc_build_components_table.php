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
        Schema::create('pc_build_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pc_build_id'); // Liên kết với bảng pc_builds
            $table->unsignedBigInteger('product_id'); // Liên kết với bảng products
            $table->integer('quantity')->default(1); // Số lượng linh kiện
            $table->timestamps();

            $table->foreign('pc_build_id')->references('id')->on('pc_builds');
            $table->foreign('product_id')->references('id')->on('products');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_build_components');
    }
};
