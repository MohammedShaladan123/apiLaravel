<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name', 'description', 'price', 'quantity', 'image', 'category_id', 'brand_id'];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
