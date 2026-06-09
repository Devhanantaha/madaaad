<?php

namespace Core\Repositories;

use Core\Models\TlAd;
use Core\Models\TlAdTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdRepository
{
    /**
     * Get Ads List
     */
    public function getAds($data = ['*'], $match_case = [], $paginate = null, $search = '')
    {
        $ads = DB::table('tl_ads')
            ->leftJoin('users', 'users.id', '=', 'tl_ads.seller_id')
            ->leftJoin('seller_shops', 'seller_shops.id', '=', 'tl_ads.shop_id')
            ->orderBy('tl_ads.sort_order', 'asc')
            ->orderBy('tl_ads.id', 'desc')
            ->where($match_case);

        $ads = $ads->where(function ($query) use ($search) {
            $query->where('users.name', 'like', "%$search%");
        });

        $ads = $ads->select($data);

        if ($paginate) {
            return $ads->paginate($paginate);
        }

        return $ads->get();
    }

    /**
     * Find Ad
     */
    public function findAd($id)
    {
        return TlAd::find($id);
    }

    /**
     * Create / Update Ad
     */
    public function adCreateUpdate($request, $id = null)
    {
        $ad = TlAd::firstOrNew(['id' => $id]);

        $ad->image = $request['image'] ?? null;
        $ad->seller_id = $request['seller_id'] ?? Auth::id();
        $ad->shop_id = $request['shop_id'] ?? null;

        $ad->sort_order = $request['sort_order'] ?? 0;
        $ad->status = $request['status'] ?? 1;

        $ad->save();

        return $ad;
    }

    /**
     * Translation (multi-language)
     */
    public function adUpdateTranslation($request)
    {
        if ($request['lang'] && $request['lang'] != getDefaultLang()) {

            $translation = TlAdTranslation::firstOrNew([
                'ad_id' => $request['id'],
                'lang' => $request['lang']
            ]);

            $translation->title = xss_clean($request['title']);
            $translation->description = xss_clean($request['description']);

            $translation->save();
        } else {
            $this->adCreateUpdate($request, $request['id']);
        }
    }

    /**
     * Delete Ad
     */
    public function deleteAd($id)
    {
        $ad = $this->findAd($id);

        if (!$ad) {
            return [
                'status' => 'error',
                'message' => 'Ad not found',
            ];
        }

        $ad->delete();

        return [
            'status' => 'success',
            'message' => 'Ad deleted successfully',
        ];
    }

    /**
     * Bulk Delete
     */
    public function bulkDeleteAds($ids)
    {
        TlAd::whereIn('id', $ids)->delete();
    }

    /**
     * Change Status (active/inactive)
     */
    public function changeStatus($id)
    {
        $ad = $this->findAd($id);

        if (!$ad) return false;

        $ad->status = $ad->status == 1 ? 0 : 1;
        $ad->save();

        return $ad;
    }

    /**
     * Get Active Ads (for frontend)
     */
    public function getActiveAds()
    {
        return TlAd::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}