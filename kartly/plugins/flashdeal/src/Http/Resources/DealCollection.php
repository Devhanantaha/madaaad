<?php

namespace Plugin\Flashdeal\Http\Resources;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DealCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'title' => $data->translation('title', session()->get('api_locale')),
                    'permalink' => $data->permalink,
                    'start_date' => $data->start_date,
                    'end_date' => $data->end_date,
                    'background_color' => $data->background_color,
                    'text_color' => $data->text_color,
                    'background_image' => asset(getFilePath($data->background_image)),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
