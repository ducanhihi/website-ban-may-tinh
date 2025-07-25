<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'order_date', 'user_id',
        'total', 'payment', 'status', 'payment_status', 'prepay', 'postpaid'
    ];
    protected $casts = [
        'order_date' => 'datetime',
    ];

    public static function findOrFail($id)
    {
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class);
    }


}
