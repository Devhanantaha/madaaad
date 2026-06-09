<?php

namespace Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Core\Repositories\AdRepository;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    protected $ad_repository;

    public function __construct(AdRepository $ad_repository)
    {
        $this->ad_repository = $ad_repository;
    }

    /**
     * Show Ads List
     */
    public function ads(Request $request)
    {
        try {

            $data = [
                'tl_ads.id',
                'tl_ads.image',
                'tl_ads.sort_order',
                'tl_ads.status',
                'tl_ads.seller_id',
                'tl_ads.shop_id'
            ];

            $match_case = [];

            $pagination = 10;
            $search = '';

            if ($request->per_page) {
                $pagination = (int)$request->per_page;
            }

            if ($request->search) {
                $search = $request->search;
            }

            $ads = $this->ad_repository->getAds($data, $match_case, $pagination, $search);

            return view('core::base.ads.ads', compact('ads'));
        } catch (\Exception $e) {
            toastNotification('error', translate('Ads Not Found'));
            return redirect()->back();
        }
    }

    /**
     * Add Ad Page
     */
    public function addAd()
    {
        try {
            return view('core::base.ads.add_ad');
        } catch (\Exception $e) {
            toastNotification('error', translate('Something Went Wrong'));
            return redirect()->route('core.ads');
        }
    }

    /**
     * Store Ad
     */
    public function storeAd(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->ad_repository->adCreateUpdate($request);

            DB::commit();

            toastNotification('success', translate('Ad Saved Successfully'));

            return redirect()->route('core.ads.add');
        } catch (\Exception $e) {
            DB::rollBack();

            toastNotification('error', translate('Ad Saving Failed'));

            return redirect()->route('core.ads.add');
        }
    }

    /**
     * Edit Ad
     */
    public function editAd($id)
    {
        try {

            $ad = $this->ad_repository->findAd($id);

            if (!$ad) {
                toastNotification('error', translate('Ad Not Found'));
                return redirect()->route('core.ads');
            }

            return view('core::base.ads.edit_ad', compact('ad'));
        } catch (\Exception $e) {
            toastNotification('error', translate('Ad Not Found'));
            return redirect()->route('core.ads');
        }
    }

    /**
     * Update Ad
     */
    public function updateAd(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->ad_repository->adCreateUpdate($request, $id);

            DB::commit();

            toastNotification('success', translate('Ad Updated Successfully'));

            return redirect()->route('core.ads');
        } catch (\Exception $e) {
            DB::rollBack();

            toastNotification('error', translate('Ad Update Failed'));

            return redirect()->back();
        }
    }

    /**
     * Delete Ad
     */
    public function deleteAd($id)
    {
        try {
            DB::beginTransaction();

            $result = $this->ad_repository->deleteAd($id);

            DB::commit();

            toastNotification($result['status'], $result['message']);

            return redirect()->route('core.ads');
        } catch (\Exception $e) {
            DB::rollBack();

            toastNotification('error', translate('Ad Deleting Failed'));

            return redirect()->back();
        }
    }

    /**
     * Bulk Delete Ads
     */
    public function bulkDeleteAd(Request $request)
    {
        try {

            if ($request->has('data')) {
                DB::beginTransaction();

                $this->ad_repository->bulkDeleteAds($request->data);

                DB::commit();

                toastNotification('success', translate('Ads Deleted Successfully'));
            }
        } catch (\Exception $e) {
            DB::rollBack();

            toastNotification('error', translate('Bulk Delete Failed'));
        }
    }

    /**
     * Change Status
     */
    public function changeStatus($id)
    {
        try {
            DB::beginTransaction();

            $ad = $this->ad_repository->changeStatus($id);

            DB::commit();

            if ($ad) {
                toastNotification('success', translate('Status Updated'));
            } else {
                toastNotification('error', translate('Ad Not Found'));
            }

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            toastNotification('error', translate('Status Update Failed'));

            return redirect()->back();
        }
    }

    public function activeAds(Request $request)
    {
        return response()->json([
            'data' => 'HIT CONTROLLER'
        ]);
        try {

            $ads = $this->ad_repository->getActiveAds($request->lang);

            return response()->json([
                'status' => true,
                'message' => 'Active ads fetched successfully',
                'data' => $ads
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
