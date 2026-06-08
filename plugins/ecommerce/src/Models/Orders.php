<?php

namespace Plugin\Ecommerce\Models;

use Carbon\Carbon;
use Core\Models\Plugin;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Plugin\Ecommerce\Models\PaymentQueue;
use Plugin\Ecommerce\Models\PaymentMethods;
use Plugin\Ecommerce\Models\CustomerAddress;
use Plugin\Ecommerce\Models\OrderHasProducts;
use Plugin\Ecommerce\Repositories\SettingsRepository;

class Orders extends Model
{

    protected $table = "tl_com_orders";

    public function products()
    {
        return $this->hasMany(OrderHasProducts::class, 'order_id');
    }
    public function num_of_products()
    {
        return $this->products()->sum('quantity');
    }

    public function customer_info()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function guest_customer()
    {
        return $this->belongsTo(GuestCustomers::class, 'id', 'order_id');
    }

    public function billing_details()
    {
        return $this->belongsTo(CustomerAddress::class, 'billing_address');
    }

    public function shipping_details()
    {
        return $this->belongsTo(CustomerAddress::class, 'shipping_address');
    }
    public function pickup_point()
    {
        if (isActivePlugin('pickuppoint')) {
            return $this->belongsTo(\Plugin\PickupPoint\Models\PickupPoint::class, 'pickup_point_id');
        }

        return $this->belongsTo(self::class, 'id')->whereRaw('1 = 0');
    }

    public function use_coupon()
    {
        if (isActivePlugin('coupon')) {
            return $this->hasOneThrough(\Plugin\Coupon\Models\Coupons::class, \Plugin\Coupon\Models\CouponUsage::class, 'order_id', 'id');
        }
        return $this->belongsTo(self::class, 'id')->whereRaw('1 = 0');
    }

    public function payment_status_label()
    {
        if ($this->payment_status == config('ecommerce.order_payment_status.paid') && $this->OrderPaymentRequiredStatus() != config('settings.general_status.active')) {
            return 'paid';
        } else {
            return 'unpaid';
        }
    }

    public function order_payment_status()
    {
        if ($this->payment_status == config('ecommerce.order_payment_status.paid') && $this->OrderPaymentRequiredStatus() != config('settings.general_status.active')) {
            return config('ecommerce.order_payment_status.paid');
        } else {
            return config('ecommerce.order_payment_status.unpaid');
        }
    }

    public function delivery_status_label()
    {
        if ($this->delivery_status == config('ecommerce.order_delivery_status.delivered')) {
            return 'delivered';
        } else if ($this->delivery_status == config('ecommerce.order_delivery_status.pending')) {
            return "pending";
        } else if ($this->delivery_status == config('ecommerce.order_delivery_status.processing')) {
            return "processing";
        } else if ($this->delivery_status == config('ecommerce.order_delivery_status.ready_to_ship')) {
            return "Ready to ship";
        } else if ($this->delivery_status == config('ecommerce.order_delivery_status.shipped')) {
            return "shipped";
        } else {
            return 'cancelled';
        }
    }

    public function adminCancelOrAcceptOrder()
    {
        $status = config('settings.general_status.active');
        foreach ($this->products as $product) {
            if ($product->delivery_status != config('ecommerce.order_delivery_status.pending')) {
                $status = config('settings.general_status.in_active');
                break;
            }
        }
        return $status;
    }

    public function sellerCancelOrAcceptOrder($seller_id)
    {
        $status = config('settings.general_status.active');
        foreach ($this->products->where('seller_id', $seller_id) as $product) {
            if ($product->delivery_status != config('ecommerce.order_delivery_status.pending')) {
                $status = config('settings.general_status.in_active');
                break;
            }
        }
        return $status;
    }

    public function payment_method_info()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method');
    }

    public function OrderPaymentRequiredStatus()
    {
        if ($this->wallet_payment == config('settings.general_status.active') ||  $this->payment_method == 1) {
            return config('settings.general_status.in_active');
        }

        $cancelled_items = $this->products->where('delivery_status', config('ecommerce.order_delivery_status.cancelled'))->count();
        if ($cancelled_items > 0) {
            return config('settings.general_status.in_active');
        }

        $paid_items = $this->products->where('payment_status', config('ecommerce.order_payment_status.paid'))->count();
        if ($paid_items > 0) {
            return config('settings.general_status.in_active');
        }

        return config('settings.general_status.active');
    }


    public function PaymentUrl()
    {

        if ($this->OrderPaymentRequiredStatus() != config('settings.general_status.active')) {
            return null;
        }

        if ($this->OrderPaymentRequiredStatus() == config('settings.general_status.active')) {
            $payment_queue = PaymentQueue::where('order_id', $this->id)->first();
            if ($payment_queue == null) {
                return null;
            } else {
                $payment_method = PaymentMethods::find($payment_queue->payment_method_id);
                $url =  url('/') . '/payment/' . Str::slug($payment_method->name) . '/pay/' . $payment_queue->uid;

                return $url;
            }
        }
    }

    public function canCancel()
    {
        $non_cancel_items = $this->products->where('delivery_status', '!=', config('ecommerce.order_delivery_status.pending'))->count();
        if ($non_cancel_items > 0) {
            return config('settings.general_status.in_active');
        }

        $time_unit = SettingsRepository::getEcommerceSetting('cancel_order_time_limit_unit');
        $time = (int) SettingsRepository::getEcommerceSetting('cancel_order_time_limit');
        if ($time_unit == config('ecommerce.time_unit.Days')) {
            $present_date = Carbon::now()->startOfDay();
            $expire_date = $this->created_at->addDay($time);
            if ($present_date <= $expire_date) {
                return config('settings.general_status.active');
            } else {
                return config('settings.general_status.in_active');
            }
        } else if ($time_unit == config('ecommerce.time_unit.Hours')) {
            $present_date = Carbon::now();
            $expire_date = $this->created_at->addHour($time);
            if ($present_date <= $expire_date) {
                return config('settings.general_status.active');
            } else {
                return config('settings.general_status.in_active');
            }
        } else if ($time_unit == config('ecommerce.time_unit.Minutes')) {
            $present_date = Carbon::now();
            $expire_date = $this->created_at->addMinute($time);
            if ($present_date <= $expire_date) {
                return config('settings.general_status.active');
            } else {
                return config('settings.general_status.in_active');
            }
        } else {
            return config('settings.general_status.in_active');
        }

        return config('settings.general_status.in_active');
    }
}
