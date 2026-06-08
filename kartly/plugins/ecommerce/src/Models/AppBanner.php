<?php

namespace Plugin\Ecommerce\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Plugin\Ecommerce\Models\AppBannerTranslation;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppBanner extends Model
{
    protected $table = 'app_banners';

    public function translation($field = '', $lang = false)
    {
        $lang == false ? App::getLocale() : $lang;
        $translations = $this->translations->where('lang', $lang)->first();
        return $translations != null ? $translations->$field : $this->$field;
    }


    public function translations(): HasMany
    {
        return $this->hasMany(AppBannerTranslation::class);
    }
}
