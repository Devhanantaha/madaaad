<?php

namespace Core\Http\Controllers;

use AppLoader;

use Core\Models\Plugin;
use Core\Models\Themes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public $system_latest_version = '1.3.0';

    /**
     * Will clear system  cache
     */
    public function clearSystemCache()
    {
        try {
            cache_clear();
            toastNotification('success', translate('Cache clear successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            toastNotification('error', translate('Cache clear failed'));
        }
    }

    /**
     * Will clear system  cache
     */
    public function clearSystemCacheFromApi()
    {
        try {
            cache_clear();
            return response()->json(
                [
                    'success' => true,
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

    public function activateLicense(Request $request)
    {
        $request->validate([
            'license' => 'required|max:350'
        ]);

        $res = AppLoader::createApp($request['license'], false);
        if ($res == 'SUCCESS') {
            $this->updateSystemVersion($this->system_latest_version);
            $this->updatePluginVersion($this->system_latest_version, $request['license']);
            $this->updateThemeVersion($this->system_latest_version, $request['license']);
            $this->setProductSeller();
            return redirect()->route('core.admin.welcome');
        }

        return $res;
    }

    public function storePurchaseKey(Request $request)
    {
        $request->validate([
            'license' => 'required'
        ]);

        return  AppLoader::validateApp($request['license']);
    }

    /**
     * Will update system version
     */

    public function updateSystemVersion($updated_version)
    {
        try {
            DB::beginTransaction();
            set_setting('system_version', $updated_version);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Will updated plugin versions
     */
    public function updatePluginVersion($updated_version, $purchase_key)
    {
        try {
            DB::beginTransaction();
            Plugin::update(
                [
                    'version' => $updated_version,
                    'unique_indentifier' => $purchase_key
                ]
            );
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }
    /**
     * Will updated theme versions
     */
    public function updateThemeVersion($updated_version, $purchase_key)
    {
        try {
            DB::beginTransaction();
            Themes::query()->update(
                [
                    'version' => $updated_version,
                    'unique_indentifier' => $purchase_key
                ]
            );

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Will set products seller
     */
    public function setProductSeller()
    {
        //Update Product
        \Plugin\Ecommerce\Models\Product::whereNull('supplier')->update(
            [
                'supplier' => getSupperAdminId()
            ]
        );

        //Update page 
        \Core\Models\TlPage::whereNull('user_id')->update(
            [
                'user_id' => getSupperAdminId()
            ]
        );
    }
}
