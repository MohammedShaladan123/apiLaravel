<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = ['name', 'discount_percentage', 'start_date', 'end_date', 'product_id'];

    public $timestamps = true;

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
