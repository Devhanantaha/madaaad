<?php

namespace Plugin\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Plugin\Ecommerce\Models\Product;

class AppBannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'title' => $data->translation('title', session()->get('api_locale')),
                    'image' => asset(getFilePath($data->translation('image', session()->get('api_locale')), false)),
                    'value' => $this->get_value($data),
                    'type' => $data->type
                ];
            })
        ];
    }

    public function get_value($data)
    {
        if ($data->type == 'product') {
            $product = Product::find($data->url);
            return $product != null ? $product->permalink : $data->url;
        }

        return $data->url;
    }
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
