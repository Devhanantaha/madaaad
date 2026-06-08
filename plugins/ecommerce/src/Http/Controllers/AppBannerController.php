<?php

namespace Plugin\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Plugin\Ecommerce\Models\AppBanner;
use Plugin\Ecommerce\Models\AppBannerTranslation;
use Plugin\Ecommerce\Http\Resources\AppBannerCollection;

class AppBannerController extends Controller
{
    public function index()
    {
        $banners = AppBanner::with('translations')->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('plugin/ecommerce::app.banner.list', ['banners' => $banners]);
    }

    public function add()
    {
        return view('plugin/ecommerce::app.banner.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'resource_type' => 'required',
            'image' => 'required',
        ]);

        $banner = new AppBanner();
        $banner->title = $request->title;
        $banner->type = $request->resource_type;
        $banner->image = $request->image;
        $banner->status = config('settings.general_status.active');
        $banner_url = "";
        foreach (config('ecommerce.app_banner_type') as $key => $value) {
            if ($key == $request->resource_type) {
                $banner_url = $request[$value];
            }
        }
        $banner->url = $banner_url;

        $banner->save();
        toastNotification('success', translate('Banner added successfully'), 'Success');
        return redirect()->route('plugin.ecommerce.app.configuration.banner.list');
    }

    public function edit($id, Request $request)
    {
        $banner = AppBanner::find($id);
        return view('plugin/ecommerce::app.banner.edit', [
            'banner' => $banner,
            'lang' => $request->lang ?? getDefaultLang(),
            'languages' => getAllLanguages()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $banner = AppBanner::find($request->id);
            if ($banner == null) {
                toastNotification('error', translate('Banner not found'), 'Failed');
                return redirect()->back();
            }

            if ($request->lang == getDefaultLang()) {
                $banner->title = $request->title;
                $banner->type = $request->resource_type;
                $banner->image = $request->image;
                $banner->status = config('settings.general_status.active');
                $banner_url = "";
                foreach (config('ecommerce.app_banner_type') as $key => $value) {
                    if ($key == $request->resource_type) {
                        $banner_url = $request[$value];
                    }
                }
                $banner->url = $banner_url;
                $banner->save();
            } else {

                $banner_translation = AppBannerTranslation::firstOrCreate([
                    'app_banner_id' => $request->id,
                    'lang' => $request->lang,
                ]);
                $banner_translation->title = $request->title;
                $banner_translation->image = $request->image;
                $banner_translation->save();
            }

            DB::commit();
            toastNotification('success', translate('Banner updated successfully'), 'Success');
            return redirect()->route('plugin.ecommerce.app.configuration.banner.list');
        } catch (\Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Banner update failed'), 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $banner = AppBanner::find($request->id);
            if ($banner == null) {
                toastNotification('error', translate('Banner not found'), 'Failed');
                return redirect()->back();
            }
            $banner->delete();
            DB::commit();
            toastNotification('success', translate('Banner deleted successfully'), 'Success');
            return redirect()->route('plugin.ecommerce.app.configuration.banner.list');
        } catch (\Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Banner delete failed'), 'Failed');
            return redirect()->back();
        }
    }

    public function activeAppBanner()
    {
        $banners = AppBanner::where('status', config('settings.general_status.active'))->get();
        return new AppBannerCollection($banners);
    }
}
