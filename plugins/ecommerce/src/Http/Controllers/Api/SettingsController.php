<?php

namespace Plugin\Ecommerce\Http\Controllers\Api;

use Core\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Core\Repositories\SettingsRepository as CoreSettingRepository;
use Plugin\Ecommerce\Models\Country;
use Plugin\Ecommerce\Models\Currency;
use Plugin\Ecommerce\Repositories\SettingsRepository;

class SettingsController extends Controller
{

    /**
     * Will return all  active languages
     * Will return active currencies
     * Will return active countries
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function siteProperties(Request $request)
    {
        try {
            $languages = Cache::rememberForever('active-languages', function () {
                return Language::where('status', config('settings.general_status.active'))
                    ->select('id', 'native_name as title', 'native_name as native_title', 'code')
                    ->get();
            });

            $currencies = Cache::rememberForever('active-currencies', function () {
                return Currency::where('status', config('settings.general_status.active'))
                    ->select('id', 'name', 'code', 'symbol', 'conversion_rate', 'position', 'thousand_separator', 'decimal_separator', 'number_of_decimal')
                    ->get();
            });

            $siteProperties = Cache::rememberForever('site-properties', function () {
                return CoreSettingRepository::SiteProperties();
            });

            $site_Settings = Cache::rememberForever('ecommerce-settings', function () {
                return  SettingsRepository::siteSettings();
            });

            $default_currency = Cache::rememberForever('default-currency', function () {
                return SettingsRepository::defaultCurrency();
            });

            $default_language = Cache::rememberForever('default-language', function () {
                return Language::where('code', defaultLanguage())->select('id', 'native_name as title', 'native_name as native_title', 'code')->first();
            });

            return response()->json(
                [
                    'success' => true,
                    'languages' => $languages,
                    'default_language' => $default_language,
                    'currencies' => $currencies,
                    'default_currency' => $default_currency,
                    'siteProperties' => $siteProperties,
                    'site_settings' => $site_Settings
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }

    /**
     * Will return countries phone code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function phoneCodes()
    {
        try {
            $phone_codes = Country::where('status', config('settings.general_status.active'))->pluck('phone_code');
            return response()->json(
                [
                    'success' => true,
                    'phone_codes' => $phone_codes
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false
                ]
            );
        }
    }
}
