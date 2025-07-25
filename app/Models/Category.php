<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

    use HasFactory;
    protected $fillable = ['id', 'name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // PC component categories
    public static function getPcCategories()
    {
        return self::whereIn('name', [
            'CPU', 'GPU', 'RAM', 'Motherboard', 'Storage', 'PSU', 'Case', 'Cooling'
        ])->get();
    }
}
