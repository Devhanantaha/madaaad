<?php

namespace Plugin\Ecommerce\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class PaymentQueue extends Model
{
    protected $table = 'payment_queues';


    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (empty($model->uid)) {
                $model->uid = Str::uuid()->toString() . '-' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $model->save();
            }
        });
    }
}
