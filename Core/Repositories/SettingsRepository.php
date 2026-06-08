<?php

namespace Core\Repositories;

use Illuminate\Support\Facades\DB;

class SettingsRepository
{
    
    /**
     * Will return site properties
     */
    public static function SiteProperties()
    {
        try {
            $data = [
                'logo' => getFilePath(get_setting('white_background_logo'), false),
                'logo_dark' => getFilePath(get_setting('black_background_logo'), false),
                'mobile_logo' => getFilePath(get_setting('white_mobile_background_logo'), false),
                'mobile_dark_logo' => getFilePath(get_setting('black_mobile_background_logo'), false),
                'site_title' => get_setting('site_title') != null ? get_setting('site_title') : get_setting('system_name'),
                'site_name' => get_setting('system_name'),
                'site_motto' => get_setting('site_moto'),
                'favicon' => getFilePath(get_setting('favicon'), false),
                'sticky_black_logo' => getFilePath(get_setting('sticky_black_background_logo'), false),
                'sticky_logo' => getFilePath(get_setting('sticky_background_logo'), false),
                'sticky_black_mobile_logo' => getFilePath(get_setting('sticky_black_mobile_background_logo'), false),
                'sticky_mobile_logo' => getFilePath(get_setting('sticky_mobile_background_logo'), false),
                'copyright' => get_setting('copyright_text'),
                'app_demo' => env('APP_DEMO') == true ? 1 : 2,
            ];

            return $data;
        } catch (\Exception $e) {
            return NULL;
        } catch (\Error $e) {
            return NULL;
        }
    }

    /**
     * Will return site seo
     */
    public static function siteSeoProperties()
    {
        try {

            $site_name = get_setting('system_name') != null ? get_setting('system_name') : get_setting('site_title');
            $site_motto = get_setting('site_moto') != null ? ' | ' . get_setting('site_moto') : '';
            $site_title = $site_name . '' . $site_motto;
            $meta_title = get_setting('site_meta_title') != null ? get_setting('site_meta_title') : get_setting('system_name');

            $data = [
                'site_title' => $site_title,
                'site_meta_title' => $meta_title,
                'site_meta_description' => get_setting('site_meta_description'),
                'site_meta_keywords' => get_setting('site_meta_keywords'),
                'site_meta_image' => getFilePath(get_setting('site_meta_image'), false),
            ];
            return $data;
        } catch (\Exception $e) {
            return NULL;
        } catch (\Error $e) {
            return NULL;
        }
    }
}