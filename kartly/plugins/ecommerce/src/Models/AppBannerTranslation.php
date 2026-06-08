<?php

namespace Plugin\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class AppBannerTranslation extends Model
{
    protected $table = 'app_banner_translations';
    protected $guarded = ['id'];

    protected $fillable = [
        'lang',
        'app_banner_id'
    ];
}
