<?php

namespace Plugin\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Plugin\Ecommerce\Models\EcommerceConfig;
use Plugin\Ecommerce\Repositories\CurrencyRepository;
use Plugin\Ecommerce\Repositories\SettingsRepository;

class SettingsController extends Controller
{
    protected $settings_repository;
    protected $currency_repository;

    public function __construct(SettingsRepository $settings_repository, CurrencyRepository $currency_repository)
    {
        $this->settings_repository = $settings_repository;
        $this->currency_repository = $currency_repository;
    }
    /**
     * Will return ecommerce settings
     * 
     * @return mixed
     */
    public function ecommerceConfig()
    {
        $currencies = $this->currency_repository->currencies(config('settings.general_status.active'));
        return view('plugin/ecommerce::ecommerce-settings.settings')->with(
            [
                'currencies' => $currencies,
            ]
        );
    }
    /**
     * Will update ecommerce settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEcommerceSettings(Request $request)
    {
        //Update in-house shop
        if (isActivePlugin('multivendor') && $request->has('tab') && $request->get('tab') == 'shop') {
            if ($request['shop_name'] == null) {
                throw ValidationException::withMessages([
                    'shop_name' => ['Shop name is required'],
                ]);
            }

            if ($request['shop_slug'] == null) {
                throw ValidationException::withMessages([
                    'shop_slug' => ['Shop slug is required'],
                ]);
            }

            $supper_admin_id = getSupperAdminId();
            if ($supper_admin_id == null) {
                throw ValidationException::withMessages([
                    'admin' => ['No super user found'],
                ]);
            }

            $shop = \Plugin\Multivendor\Models\SellerShop::where('seller_id', $supper_admin_id)->first();


            if (\Plugin\Multivendor\Models\SellerShop::where('shop_slug', $request['shop_slug'])->whereNot('id', $shop?->id)->exists()) {
                throw ValidationException::withMessages([
                    'shop_slug' => ['Shop slug is already taken'],
                ]);
            }

            if ($shop == null) {
                $shop = new \Plugin\Multivendor\Models\SellerShop;
            }

            //Store or update in-house shop
            $shop->shop_name = $request['shop_name'];
            $shop->shop_slug = $request['shop_slug'];
            $shop->seller_id = $supper_admin_id;
            $shop->shop_phone = $request['shop_phone'];
            $shop->logo = $request['shop_logo'];
            $shop->shop_banner = $request['shop_banner'];
            $shop->shop_address = $request['shop_address'];
            $shop->meta_title = $request['shop_meta_title'];
            $shop->meta_description = $request['shop_meta_description'];
            $shop->meta_image = $request['shop_meta_image'];
            $shop->status = config('settings.general_status.active');
            $shop->save();
            //store in-house shop id
            $config = EcommerceConfig::firstOrCreate(['key_name' => 'in_house_shop_id']);
            $config->key_value = $shop->id;
            $config->save();
        }


        $res = $this->settings_repository->updateEcommerceSettings($request);

        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
