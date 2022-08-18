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
    /**
     * @return GoogleAdsService
     */
    public function getGoogleAdsService(): GoogleAdsService
    {
        return new GoogleAdsService();
    }

    /**
     * @return AuthService
     */
    public function getGoogleAdsAuthService(): AuthService
    {
        return new AuthService();
    }

    /**
     * Gets Daily Data of all sub accounts
     *
     * @param null $startDate
     * @param null $endDate
     * @return JsonResponse
     */
    public function getDailyDataFromService($startDate=null, $endDate=null): JsonResponse
    {
        try {
            $dateRange['startDate'] = date('Y-m-d',strtotime($startDate??'yesterday'));
            if(strtotime('now')>strtotime("12:00 a.m.") && strtotime('now')<strtotime("01:00 a.m.")){
                $dateRange['startDate'] = date('Y-01-01');
            }
            $dateRange['endDate'] = date('Y-m-d',strtotime($endDate??'today'));
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

    /** Handles multiple daily data if date range is provided
     * @param Request $request
     * @return JsonResponse
     */
    public function getDailyData(Request $request): JsonResponse
    {
        return $this->getDailyDataFromService($request->startDate??null, $request->endDate??null);
    }

    public function getMonthlyData(){
        $today = date("Y-m-d");
        $lastDayOfThisMonth = date("Y-m-t");
        if($today==$lastDayOfThisMonth){
            if($this->getDailyDataFromService(date("Y-m-01"),date("Y-m-t"))) return "Date: ". $today."--IS THE LAST DAY OF MONTH(".$lastDayOfThisMonth."),-- Monthly data Updated Successfully";
            else return "Could not update Monthly data";
        }else return "Date: ". $today." not last day of month(".$lastDayOfThisMonth."), Monthly Data was not updated";
    }

}
