<?php

namespace Plugin\Flashdeal\Http\Resources;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DealProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data?->product->id,
                    'has_variant' => (int) $data?->product->has_variant,
                    'name' => $data?->product->translation('name', session()->get('api_locale')),
                    'slug' => $data?->product->permalink,
                    'thumbnail_image' => asset(getFilePath($data?->product->thumbnail_image, true, '250x250')),
                    'base_price' => (float) $this->base_price($data?->product),
                    'price' => $data?->product->unit_price,
                    'discount' => $this->productDiscount($data?->product),
                    'quantity' => (float) $this->stock($data?->product),
                    'unit' => $data?->product->unit_info != null ? $data?->product->unit_info->translation('name', session()->get('api_locale')) : null,
                    'min_qty' => $data?->product->min_item_on_purchase != null ? $data?->product->min_item_on_purchase : 0,
                    'max_qty' => $data?->product->max_item_on_purchase != null ? $data?->product->max_item_on_purchase : 0,
                    'total_reviews' => $this->rating($data?->product),
                    'avg_rating' => $this->avgRating($data?->product),
                    'seller' => $data?->product->supplier,
                    'shop' => isActivePlugin('multivendor') && $data?->product->seller != null ? $data?->product->seller->shop : null
                ];
            })
        ];
    }

    public function productDiscount($data)
    {
        $discount = Cache::remember('product-discount-' . $data->name, 60 * 60, function () use ($data) {
            return  $data->applicableDiscount();
        });
        return $discount;
    }

    public function rating($data)
    {
        $total_rating = count($data->reviews);
        return $total_rating;
    }
    public function avgRating($data)
    {
        $avg = $data->reviews->avg('rating');
        return $avg != null ? $avg : 0;
    }
    public function base_price($item)
    {


        if ($item->has_variant == config('ecommerce.product_variant.single')) {
            return $item->single_price != null ? $item->single_price->unit_price : 0;
        } else {
            return 2;
            return $item->variations != null ? $item->variations[0]->unit_price : 0;
        }

        return 3;
    }
    public function stock($data)
    {
        if ($data->has_variant == config('ecommerce.product_variant.single')) {
            return $data->single_price != null ? $data->single_price->quantity : 0;
        } else {
            return $data->variations != null ? array_reduce($data->variations->toArray(), function ($qty, $item) {
                $qty += $item['quantity'];
                return $qty;
            }) : 0;
        }
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
