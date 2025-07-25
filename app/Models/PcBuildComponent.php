<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcBuildComponent extends Model
{
    use HasFactory;

    // Chỉ định rõ tên bảng
    protected $table = 'pc_build_components';

    protected $fillable = [
        'pc_build_id',
        'product_id',
        'quantity'
    ];

    public function pcBuild()
    {
        return $this->belongsTo(PcBuild::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
