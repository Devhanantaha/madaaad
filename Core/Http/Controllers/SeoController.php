<?php

namespace Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SeoController extends Controller
{
    /**
     * Will redirect seo settings page
     * 
     */
    public function seoSettings()
    {
        return view('core::base.business.seo.site_seo');
    }
    /**
     * Will update seo settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function updateSeoSettings(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->except(['_token']) as $key => $value) {
                set_setting($key, $value);
            }
            DB::commit();
            $this->resetSeoCache();
            toastNotification('success', translate('Seo update successfully'));
            return to_route('core.seo.settings');
        } catch (\Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Seo update failed'));
            return redirect()->back();
        }
    }


    /**
     * Will reset Seo cache
     */
    public function resetSeoCache()
    {
        cache()->forget('site-seo-properties');
        cache()->rememberForever('site-seo-properties', function () {
            return \Core\Repositories\SettingsRepository::siteSeoProperties();
        });
    }
}
