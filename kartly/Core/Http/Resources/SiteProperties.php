<?php

namespace Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteProperties extends JsonResource
{
    public function toArray($request)
    {
        return [
            'logo' => getFilePath(get_setting('white_background_logo'), false),
            'logo_dark' => getFilePath(get_setting('black_background_logo'), false),
            'mobile_logo' => getFilePath(get_setting('white_mobile_background_logo'), false),
            'mobile_dark_logo' => getFilePath(get_setting('black_mobile_background_logo'), false),
            'site_title' => get_setting('site_title') != null ? get_setting('site_title') : get_setting('system_name'),
            'site_name' => get_setting('system_name'),
            'favicon' => getFilePath(get_setting('favicon'), false),
            'sticky_black_logo' => getFilePath(get_setting('sticky_black_background_logo'), false),
            'sticky_logo' => getFilePath(get_setting('sticky_background_logo'), false),
            'sticky_black_mobile_logo' => getFilePath(get_setting('sticky_black_mobile_background_logo'), false),
            'sticky_mobile_logo' => getFilePath(get_setting('sticky_mobile_background_logo'), false),
            'copyright' => get_setting('copyright_text')
        ];
    }
}
