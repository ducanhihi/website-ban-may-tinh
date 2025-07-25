<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    public $timestamps = false; // Nếu bảng orderdetails không có created_at, updated_at

    protected $table = 'orderdetails'; // Sửa đổi tên bảng ở đây
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'invoice_id', // Thêm trường invoice_id

    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serial()
    {
        return $this->hasMany(Serial::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class); // Mối quan hệ với Invoice
    }
}
