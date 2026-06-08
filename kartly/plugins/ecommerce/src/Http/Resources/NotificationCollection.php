<?php

namespace Plugin\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'message' => $data->data['message'] ?? null,
                    'link' => $data->data['link'] ?? null,
                    'type' => $data->data['type'] ?? null,
                    'param' => $data->data['id'] ?? null,
                    'time' => $data->created_at->diffForHumans()
                ];
            })
        ];
    }
}
