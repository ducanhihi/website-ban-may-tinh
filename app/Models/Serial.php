<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory;

    protected $table = 'serial'; // Sửa đổi tên bảng ở đây
    protected $fillable = [
        'serial_number',
        'product_id',
        'order_detail_id',
    ];

    public function orderdetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id'); // Chỉ định rõ tên cột khóa ngoại
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
