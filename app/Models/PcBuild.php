<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcBuild extends Model
{
    use HasFactory;

    // Chỉ định rõ tên bảng
    protected $table = 'pc_builds';

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function components()
    {
        return $this->hasMany(PcBuildComponent::class);
    }

    // Tính tổng giá trị build
    public function getTotalPriceAttribute()
    {
        return $this->components->sum(function ($component) {
            return ($component->product->final_price ?? $component->product->price_out) * $component->quantity;
        });
    }

    // Lấy danh sách sản phẩm theo category
    public function getComponentsByCategory()
    {
        $components = [];
        foreach ($this->components as $component) {
            $categoryName = $component->product->category->name;
            if (!isset($components[$categoryName])) {
                $components[$categoryName] = [];
            }
            $components[$categoryName][] = $component;
        }
        return $components;
    }
}
