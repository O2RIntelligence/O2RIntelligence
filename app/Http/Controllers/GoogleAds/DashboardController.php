<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Resources\GoogleAds\HourlyData;
use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Http\Resources\GoogleAds\SubAccountResource;
use App\Model\GoogleAds\DailyData;
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
            $dailyProjection = $this->getDailyProjectionData($startDate, $endDate);
            $monthlyProjection = $this->getMonthlyProjectionData($startDate, $endDate);
            $hourlyProjection = $this->getHourlyProjectionData($startDate, $endDate);

            return response()->json([
                'accountInformation' => $accountInformation,
                'dailyProjection' => $dailyProjection,
                'monthlyProjection' => $monthlyProjection,
                'hourlyProjection' => $hourlyProjection,
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


    public function getDailyProjectionData($startDate, $endDate)
    {
        try {
            $dailyCost = $this->getDailyCostData($startDate);
            $dailyRunRate = $this->getDailyRunRateData($startDate);
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

    public function getDailyCostData($date) //Sum of Total Cost Today/Till Today
    {
        try {
            $dayCount = date('d', strtotime($date));
            if(date('Y-m-d') == $date) {
                $dailyCost = HourlyData::sum('cost')/$dayCount;
            }else{
                $dataFromDate = DailyData::where('date', $date)->sum('cost');
                $dailyCost = $dataFromDate->cost/$dayCount;
            }
            return $dailyCost;
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getDailyRunRateData($date) //Today Spent/ today Hours * 24
    {
        try {
            if(date('Y-m-d') == $date) {
                $dailyRunRate = (HourlyData::sum('cost')/HourlyData::count())*24;
            }else{
                $dataFromDate = DailyData::where('date', $date)->sum('cost');
                $dailyRunRate = ($dataFromDate->cost/24)*24;
            }
            return $dailyRunRate;
        } catch (Exception $exception) {
            dd($exception);
        }
    }


}
