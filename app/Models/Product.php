<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
protected $fillable = [
    'name',
    'price',
    'stock',
    'category',
    'barcode',
    'qr_code_path',
    'image_path',
];

public function isLowStock()
{
    return $this->stock <= 5; // You can adjust the threshold here
}

public function category()
{
    return $this->belongsTo(Category::class);
}
}
