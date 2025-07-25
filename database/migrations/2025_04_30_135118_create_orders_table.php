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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->dateTime('order_date');
            $table->integer('total');
            $table->string('landing_code')->nullable();
            $table->string('shipping_unit')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->string('payment');
            $table->string('payment_status')->nullable();;
            $table->string('prepay')->nullable();;
            $table->string('postpaid')->nullable();;
            $table->text('note')->nullable();
            $table->string('status')->default('Chờ xác nhận'); // Sửa ở đây
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
