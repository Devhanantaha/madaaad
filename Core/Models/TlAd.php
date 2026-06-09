<?php

namespace Core\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Plugin\Multivendor\Models\SellerShop;

class TlAd extends Model
{
    protected $table = 'tl_ads';
    protected $guarded = [];

    public function ad_translations()
    {
        return $this->hasMany(TlAdTranslation::class, 'ad_id');
    }

    public function translation($field = '', $lang = null)
    {
        $lang = $lang ?: App::getLocale();

        $translation = $this->ad_translations()
            ->where('lang', $lang)
            ->first();

        return $translation?->$field;
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function shop()
    {
        return $this->belongsTo(SellerShop::class, 'shop_id');
    }
}