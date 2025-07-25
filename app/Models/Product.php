<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'product_code',
        'name',
        'price_out',
        'price_in',
        'discount_percent',
        'discount_direct',
        'quantity',
        'description',
        'image',
        'category_id',
        'brand_id',
        'details'
    ];
    public function images() {
        return $this->hasMany(Image::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }




    protected $casts = [
        'details' => 'array'
    ];


    // Giá cuối cùng sau giảm giá
    public function getFinalPriceAttribute()
    {
        $price = $this->price_out;

        if ($this->discount_percent) {
            $price -= ($price * $this->discount_percent / 100);
        }

        if ($this->discount_direct) {
            $price -= $this->discount_direct;
        }

        return max(0, $price);
    }

// Kiểm tra có giảm giá hay không
    public function getHasDiscountAttribute()
    {
        return ($this->discount_percent > 0 || $this->discount_direct > 0);
    }


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/products/' . $this->image) : asset('images/no-image.png');
    }


}
