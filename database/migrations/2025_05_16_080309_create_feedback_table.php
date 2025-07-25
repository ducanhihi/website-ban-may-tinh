<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY
            $table->unsignedBigInteger('product_id'); // BIGINT UNSIGNED NOT NULL
            $table->unsignedBigInteger('user_id'); // BIGINT UNSIGNED NOT NULL
            $table->unsignedBigInteger('order_id'); // BIGINT UNSIGNED NOT NULL
            $table->integer('rating')->check('rating >= 1 AND rating <= 5'); // INT NOT NULL CHECK (rating >= 1 AND rating <= 5)
            $table->text('comment')->nullable(); // TEXT NULL
            $table->timestamps(); // created_at, updated_at

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
