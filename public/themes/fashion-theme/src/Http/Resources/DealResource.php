<?php

namespace Theme\FashionTheme\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Session;

class DealResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'title' => $this->translation('title', session()->get('api_locale')),
            'permalink' => $this->permalink,
            'deadline' => $this->end_date,
            'text_color' => $this->text_color,
            'background_image' => asset(getFilePath($this->background_image)),
        ];
    }
}
