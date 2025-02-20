<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = ['user_id', 'total_price', 'status', 'coupon_code'];

    public $timestamps = true;

 // هاي عشان يتحقق اذا  الكود صالح او لا
    public function isValid()
    {
        return Carbon::now()->lt(Carbon::parse($this->expires_at));
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'coupon_code', 'code');
}

}
