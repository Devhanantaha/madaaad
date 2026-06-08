<?php

namespace Plugin\Ecommerce\Observer;

use Core\Models\User;
use Plugin\Ecommerce\Models\OrderHasProducts;
use Plugin\Ecommerce\Models\ProductHasCategories;
use Plugin\Ecommerce\Repositories\EcommerceNotification;

class SellerEarningObserver
{

    public function updated(OrderHasProducts $order_product)
    {
        if (isActivePlugin('multivendor')) {
            $item_info = OrderHasProducts::where('id', $order_product->id)->first();
            $seller_info = User::where('id', $item_info->seller_id)->select('id', 'user_type')->first();

            if ($seller_info != null && $seller_info->user_type == config('ecommerce.user_type.seller') && $item_info->payment_status == config('ecommerce.order_payment_status.paid') && $item_info->delivery_status == config('ecommerce.order_delivery_status.delivered')) {

                $seller_earning = \Plugin\Multivendor\Models\SellerEarnings::where('order_package_id', $item_info->id)
                    ->where('seller_id', $item_info->seller_id)
                    ->where('order_id', $item_info->order_id)
                    ->where('product_id', $item_info->product_id)
                    ->first();
                //Updated exiting earning status
                if ($seller_earning != null) {
                    $updated_status = $seller_earning->status;

                    if ($item_info->return_status == config('ecommerce.product_return_status.processing')) {
                        $updated_status = config('ecommerce.seller_earning_status.pending');
                    }

                    if ($item_info->return_status == config('ecommerce.product_return_status.returned')) {
                        $updated_status = config('ecommerce.seller_earning_status.refunded');
                    }

                    if ($item_info->return_status == config('ecommerce.product_return_status.return_cancel')) {
                        $updated_status = config('ecommerce.seller_earning_status.approve');
                    }

                    $seller_earning->status = $updated_status;
                    $seller_earning->save();

                    //Send notification to seller
                    $message = "Change your earning status";
                    EcommerceNotification::sendEarningNotificationToSeller($item_info->seller_id, $message);
                }

                if ($seller_earning == null) {

                    //Calculate admin commission
                    $commission_rate = 0;
                    if (get_setting('category_wise_seller_commission') == config('settings.general_status.active')) {
                        $category_commission = \Plugin\Multivendor\Models\CategoryHasCommission::whereIn('category_id', ProductHasCategories::where('product_id', $item_info->product_id)->pluck('category_id'))
                            ->select(['rate', 'category_id'])
                            ->max('rate');
                        if ($category_commission != null) {
                            $commission_rate = $category_commission;
                        } else {
                            $commission_rate = get_setting('seller_default_commission') != null ? get_setting('seller_default_commission') : 0;
                        }
                    }

                    if (get_setting('category_wise_seller_commission') != config('settings.general_status.active')) {
                        $commission_rate = get_setting('seller_default_commission') != null ? get_setting('seller_default_commission') : 0;
                    }

                    $total_amount = $item_info->totalPayableAmount();
                    $admin_commission = ($total_amount * $commission_rate) / 100;

                    //Calculate seller earning
                    $seller_total_earning = $total_amount - $admin_commission;
                    //Set Status
                    $status = config('ecommerce.seller_earning_status.pending');
                    if ($item_info->return_status == config('ecommerce.product_return_status.not_available') && $item_info->return_status == config('ecommerce.product_return_status.return_cancel')) {
                        $status = config('ecommerce.seller_earning_status.approve');
                    }
                    //Store Seller earning
                    $new_seller_earning = \Plugin\Multivendor\Models\SellerEarnings::firstOrCreate(['order_package_id' => $item_info->id, 'seller_id' => $item_info->seller_id, 'order_id' => $item_info->order_id, 'product_id' => $item_info->product_id]);
                    $new_seller_earning->earning = $seller_total_earning;
                    $new_seller_earning->admin_commission = $admin_commission;
                    $new_seller_earning->status = $status;
                    $new_seller_earning->save();
                    //Send notification to seller
                    EcommerceNotification::sendEarningNotificationToSeller($item_info->seller_id, 'Earning added to your account');
                }
            }
        }
    }
}
