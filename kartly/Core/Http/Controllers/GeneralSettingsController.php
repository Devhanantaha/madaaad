<?php

namespace Core\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Core\Repositories\SettingsRepository;
use Core\Http\Requests\GeneralSettingsRequest;

class GeneralSettingsController extends Controller
{
    protected $settings_repository;

    public function __construct(SettingsRepository $settings_repository)
    {
        $this->settings_repository = $settings_repository;
    }

    /**
     * Redirect to general settings page
     */
    public function generalSettings()
    {

        return view('core::base.business.general_settings.settings');
    }

    /**
     * store general settings
     *
     * @param  GeneralSettingsRequest $request
     * @return mixed
     */
    public function storeGeneralSettings(GeneralSettingsRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->except(['_token', 'files']) as $key => $value) {
                set_setting($key, $value);
            }


            if (isset($request['default_timezone'])) {
                setEnv('APP_TIMEZONE', str_replace(' ', '', $request['default_timezone']));
            }

            Cache::forget('default-lang');
            session()->put('locale', getDefaultLang());
            cache_clear();

            toastNotification('success', translate('Settings updated successfully'));
            DB::commit();
            return redirect()->route('core.general.settings');
        } catch (\Exception $ex) {
            DB::rollBack();
            toastNotification('error', translate('Unable to update general settings'));
            return redirect()->route('core.general.settings');
        }
    }

    /**
     * Will reset site properties cache
     */
    public function resetSitePropertiesCache()
    {
        cache()->forget('site-properties');
        Cache::rememberForever('site-properties', function () {
            return SettingsRepository::SiteProperties();
        });
    }
}
