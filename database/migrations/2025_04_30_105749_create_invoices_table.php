<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Tạo khóa chính
            $table->date('invoice_date'); // Ngày hóa đơn
            $table->integer('total_amount'); // Tổng số tiền hóa đơn
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices'); // Xóa bảng nếu cần
    }
}
