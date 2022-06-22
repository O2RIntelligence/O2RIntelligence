<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Http\Resources\GoogleAds\SubAccountResource;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\GeneralVariable;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use App\Services\GoogleAds\AuthService;
use App\Services\GoogleAds\GoogleAdsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DailyDataController extends Controller
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
     * Gets Daily Data of all sub accounts
     *
     * @return JsonResponse
     */
    public function getDailyData(Request $request){
        try {
            $dateRange['startDate'] = date('Y-m-01',strtotime($request->startDate??'today'));
            $dateRange['endDate'] = date('Y-m-d',strtotime($request->endDate??'today'));
            $dailyData = [];
            $masterAccounts =  MasterAccountResource::collection(MasterAccount::all());
            $generalVariable = GeneralVariable::get()->first();
            foreach ($masterAccounts as $key => $masterAccount) {
                $googleAdsClient = $this->getGoogleAdsAuthService()->getGoogleAdsService($masterAccount);
                $subAccounts = SubAccountResource::collection(SubAccount::all());
                foreach ($subAccounts as $subAccount) {
                    $dailyData [] = $this->getGoogleAdsService()->getDailyData($googleAdsClient,$masterAccount, $subAccount, $dateRange, $generalVariable);
                }
            }
            return response()->json(['success' => true, 'data' => $dailyData]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }

}
