<?php

namespace Plugin\Coupon\Models;

use Plugin\Coupon\Models\Coupons;
use Plugin\Ecommerce\Models\Orders;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $table = "tl_com_coupon_usages";

    protected $fillable = ['coupon_id', 'order_id'];


    public function coupon()
    {
        return $this->belongsTo(Coupons::class, 'coupon_id');
    }


    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
