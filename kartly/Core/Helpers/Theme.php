<?php

use Illuminate\Support\Facades\App;

if (!function_exists('getActiveThemeOptions')) {
    /**
     * get active theme's theme options
     *
     * @return String
     */
    function getActiveThemeOptions()
    {
        $theme = getActiveTheme();
        if ($theme != null) {
            $item = 'theme/' . $theme->location . '::backend.includes.themeOptions';
            return $item;
        }

        return null;
    }
}


if (!function_exists('getActiveTheme')) {
    /**
     * get active theme
     *
     * @return Collection
     */
    function getActiveTheme()
    {
        return App::make('ThemeManager')
            ->where('is_activated', config('settings.general_status.active'))
            ->first();
    }
}
