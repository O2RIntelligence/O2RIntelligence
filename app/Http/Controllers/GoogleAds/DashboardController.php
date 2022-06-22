<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Http\Resources\GoogleAds\SubAccountResource;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\HourlyData;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getAllDashboardData(Request $request)
    {
        try {
            $startDate = date('Y-m-d', strtotime($request->startDate??'today'));
            $endDate = date('Y-m-d', strtotime($request->endDate??'today'));

            $accountInformation = $this->getAccountInformation();
            $dailyProjection = $this->getDailyProjectionData($startDate, $accountInformation['masterAccounts'],$accountInformation['subAccounts']);
//            $monthlyProjection = $this->getMonthlyProjectionData($startDate, $endDate);
//            $hourlyProjection = $this->getHourlyProjectionData($startDate, $endDate);

            return response()->json([
                'accountInformation' => $accountInformation,
                'dailyProjection' => $dailyProjection,
//                'monthlyProjection' => $monthlyProjection,
//                'hourlyProjection' => $hourlyProjection,
            ]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }

    public function getAccountInformation(){
        try {
            $masterAccounts = MasterAccountResource::collection(MasterAccount::where('is_active',true)->where('is_online',true)->get());
            $subAccounts = SubAccountResource::collection(SubAccount::where('is_active',true)->where('is_online',true)->get());
            return array('masterAccounts'=>$masterAccounts,'subAccounts'=>$subAccounts);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


    public function getDailyProjectionData($startDate, $masterAccounts,$subAccounts)
    {
        try {
            $dailyCost = $this->getDailyCostData($startDate,$masterAccounts,$subAccounts);
            $dailyRunRate = $this->getDailyRunRateData($startDate,$masterAccounts,$subAccounts);
            return array('dailyCost' => $dailyCost, 'dailyRunRate' => $dailyRunRate);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


    public function getMonthlyProjectionData($startDate, $endDate)
    {
        try {
            $monthlyCost = $this->getMonthlyCostData($startDate);
            $monthlyRunRate = $this->getMonthlyRunRateData($startDate);
            return array('monthlyCost' => $monthlyCost, 'monthlyRunRate' => $monthlyRunRate);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


    public function getHourlyProjectionData($startDate, $endDate)
    {
        try {

        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getDailyCostData($date,$masterAccounts,$subAccounts) //Sum of Total Cost Today/Till Today
    {
        try {
            $dayCount = date('d', strtotime($date));
            $totalDailyCost = 0;
            $masterAccountData = [];
            $subAccountData = [];
            foreach($masterAccounts as $key1=>$masterAccount){
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach($subAccounts as $key2=>$subAccount){
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        if (date('Y-m-d') == $date) {
                            $dailyCost = HourlyData::where('sub_account_id', $subAccount->id)->sum('cost') / $dayCount;
                        } else {
                            $dataFromDate = DailyData::where('sub_account_id', $subAccount->id)->where('date', $date)->sum('cost');
                            $dailyCost = $dataFromDate->cost / $dayCount;
                        }
                        $totalDailyCost+=$dailyCost;
                        $subAccountData[$key2]['cost'] = empty($subAccountData[$key2]['cost'])?$dailyCost:$subAccountData[$key2]['cost']+$dailyCost;
                    }
                }
                $masterAccountData[$key1]['cost'] = empty( $masterAccountData[$key1]['cost'])?$totalDailyCost: $masterAccountData[$key1]['cost']+$totalDailyCost;

            }
            return array('totalDailyCost'=>$totalDailyCost, 'masterAccountData'=>$masterAccountData ,'subAccountData'=>$subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getDailyRunRateData($date,$masterAccounts,$subAccounts) //Today Spent/ today Hours * 24
    {
        try {
            $totalDailyRunRate = 0;
            $masterAccountData = [];
            $subAccountData = [];
            foreach($masterAccounts as $key1=>$masterAccount){
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach($subAccounts as $key2=>$subAccount){
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        if(date('Y-m-d') == $date) {
                            $dailyRunRate = (HourlyData::where('sub_account_id', $subAccount->id)->sum('cost')/HourlyData::count())*24;
                        }else{
                            $dataFromDate = DailyData::where('sub_account_id', $subAccount->id)->where('date', $date)->sum('cost');
                            $dailyRunRate = ($dataFromDate->cost/24)*24;
                        }
                        $totalDailyRunRate+=$dailyRunRate;
                        $subAccountData[$key2]['runRate'] = empty($subAccountData[$key2]['runRate'])?$dailyRunRate:$subAccountData[$key2]['runRate']+$dailyRunRate;
                    }
                }
                $masterAccountData[$key1]['runRate'] = empty( $masterAccountData[$key1]['runRate'])?$totalDailyRunRate: $masterAccountData[$key1]['runRate']+$totalDailyRunRate;

            }

            return array('totalDailyCost'=>$totalDailyRunRate, 'masterAccountData'=>$masterAccountData ,'subAccountData'=>$subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


}
