<?php

namespace Plugin\Flashdeal\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'title' => $this->translation('title', session()->get('api_locale')),
            'permalink' => $this->permalink,
            'end_date' => $this->end_date,
            'text_color' => $this->text_color,
            'background_image' => asset(getFilePath($this->background_image)),
        ];
    }
}
