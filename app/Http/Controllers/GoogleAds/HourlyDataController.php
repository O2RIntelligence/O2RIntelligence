<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Http\Resources\GoogleAds\SubAccountResource;
use App\Model\GoogleAds\GeneralVariable;
use App\Model\GoogleAds\HourlyData;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use App\Services\GoogleAds\AuthService;
use App\Services\GoogleAds\GoogleAdsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HourlyDataController extends Controller
{

    public function getGoogleAdsService(): GoogleAdsService
    {
        return new GoogleAdsService();
    }

    public function getGoogleAdsAuthService(): AuthService
    {
        return new AuthService();
    }


    /**
     * Gets Hourly Data of all sub accounts
     *
     * @return JsonResponse
     */

    public function getHourlyData(): JsonResponse
    {
        try {
            $dateRange = date('Y-m-d',strtotime('today'));
            $dailyData = [];
            $masterAccounts = MasterAccountResource::collection(MasterAccount::where('is_online', true)->get());
            foreach ($masterAccounts as $key => $masterAccount) {
                $googleAdsClient = $this->getGoogleAdsAuthService()->getGoogleAdsService($masterAccount);
                $subAccounts = SubAccountResource::collection(SubAccount::all());
                foreach ($subAccounts as $subAccount) {
                    $dailyData [] = $this->getGoogleAdsService()->getHourlyData($googleAdsClient, $masterAccount, $subAccount, $dateRange);
                }
            }
            return response()->json(['success' => true, 'data' => $dailyData]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }
}
