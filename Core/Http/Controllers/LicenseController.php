<?php

namespace Core\Http\Controllers;

use AppLoader;
use Core\Models\Plugin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LicenseController extends Controller
{

    public function index()
    {
        $licenses = AppLoader::getUserKeys();
        foreach ($licenses as $key => $license) {
            $licenses[$key]->details = null;
            $req = Http::post(config('themelooks.api_base_url') . '/api/v1/license-details', [
                'purchase_key' => $license->license_key
            ]);
            $response = $req->json();
            if (isset($response['success']) && $response['success'] != false) {
                $details = $response['data'];
                $licenses[$key]->details = $details;
            }
        }

        return view('core::base.system.license.licenses', compact('licenses'));
    }

    public function remove(Request $request)
    {
        try {
            $response = AppLoader::removeLicenseKey($request->license, $request->item);
            if ($response) {
                toastNotification('success', 'License removed successfully', 'Success');
                cache_clear();
                $this->resetPluginsCache();
                return redirect()->back();
            }
            toastNotification('error', 'Unable to remove license', 'Error');
            return redirect()->back();
        } catch (\Exception $e) {
            toastNotification('error', 'Unable to remove license', 'Error');
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function resetPluginsCache()
    {
        cache()->forget('plugins');
        cache_clear();
        Cache::rememberForever("plugins", function () {
            return Plugin::all();
        });
    }
}
