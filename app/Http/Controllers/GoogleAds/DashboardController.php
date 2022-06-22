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
            $startDate = date('Y-m-d', strtotime($request->startDate ?? 'today'));
            $endDate = date('Y-m-d', strtotime($request->endDate ?? 'today'));
            $accountInformation = $this->getAccountInformation();
            $dailyProjection = $this->getDailyCostRunRateData($startDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $monthlyProjection = $this->getMonthlyCostAndRunRateData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $dailyCostGraphData = $this->getDailyCostGraphData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $hourlyCostGraphData = $this->getHourlyCostGraphData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);

            return response()->json([
                'accountInformation' => $accountInformation,
                'dailyProjection' => $dailyProjection,
                'monthlyProjection' => $monthlyProjection,
                'dailyCostGraphData' => $dailyCostGraphData,
                'hourlyCostGraphData' => $hourlyCostGraphData,
            ]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }

    public function getAccountInformation()
    {
        try {
            $masterAccounts = MasterAccount::select('id','name','account_id','discount','revenue_conversion_rate')->where('is_active', true)->where('is_online', true)->get();
            $subAccounts = SubAccountResource::collection(SubAccount::where('is_active', true)->where('is_online', true)->get());
            return array('masterAccounts' => $masterAccounts, 'subAccounts' => $subAccounts);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getDailyCostRunRateData($date, $masterAccounts, $subAccounts)
    { //Sum of Total Cost Today/Till Today
        try {
            $dayCount = date('d', strtotime($date));
            $totalDailyCost = 0;
            $totalDailyRunRate = 0;
            $masterAccountData = [];
            $subAccountData = [];
            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        if (date('Y-m-d') == $date) {
                            $hourlyData = HourlyData::where('sub_account_id', $subAccount->id)->sum('cost');
                            $dailyCost = $hourlyData / $dayCount;
                            $dailyRunRate = ($hourlyData / HourlyData::count()) * 24;
                        } else {
                            $dataFromDate = DailyData::where('sub_account_id', $subAccount->id)->where('date', $date)->sum('cost');
                            $dailyCost = $dataFromDate / $dayCount;
                            $dailyRunRate = $dataFromDate;
                        }
                        $totalDailyCost += $dailyCost;
                        $subAccountData[$key2]['cost'] = empty($subAccountData[$key2]['cost']) ? $dailyCost : $subAccountData[$key2]['cost'] + $dailyCost;
                        $totalDailyRunRate += $dailyRunRate;
                        $subAccountData[$key2]['runRate'] = empty($subAccountData[$key2]['runRate']) ? $dailyRunRate : $subAccountData[$key2]['runRate'] + $dailyRunRate;
                    }
                }
                $masterAccountData[$key1]['cost'] = empty($masterAccountData[$key1]['cost']) ? $totalDailyCost : $masterAccountData[$key1]['cost'] + $totalDailyCost;
                $masterAccountData[$key1]['runRate'] = empty($masterAccountData[$key1]['runRate']) ? $totalDailyRunRate : $masterAccountData[$key1]['runRate'] + $totalDailyRunRate;
            }
            return array('totalDailyCost' => $totalDailyCost, 'totalDailyRunRate' => $totalDailyRunRate, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getMonthlyCostAndRunRateData($start_date, $end_date, $masterAccounts, $subAccounts)
    { //Sum of Total Cost This Month, //Spent(cost)/ thisMonth.days.count* Months Days
        try {
            $startDate = date('Y-m-01', strtotime($start_date));
            $endDate = date('Y-m-t', strtotime($end_date));
            $totalDaysThisMonth = date('d', strtotime($endDate));
            $currentDayCount = $start_date == $end_date ? date('d', strtotime($start_date)) : date_diff(date_create($start_date), date_create($end_date))->format("%a");
            $totalMonthlyCost = 0;
            $totalMonthlyRunRate = 0;
            $masterAccountData = [];
            $subAccountData = [];
            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        $monthlyCost = DailyData::where('sub_account_id', $subAccount->id)->whereBetween('date', [$startDate, $endDate])->sum('cost');
                        $totalMonthlyCost += $monthlyCost;
                        $monthlyRunRate = ($monthlyCost / $currentDayCount) * $totalDaysThisMonth;
                        $totalMonthlyRunRate += $monthlyRunRate;

                        $subAccountData[$key2]['monthlyCost'] = empty($subAccountData[$key2]['monthlyCost']) ? $monthlyCost : $subAccountData[$key2]['monthlyCost'] + $monthlyCost;
                        $subAccountData[$key2]['monthlyRunRate'] = empty($subAccountData[$key2]['monthlyRunRate']) ? $monthlyRunRate : $subAccountData[$key2]['monthlyRunRate'] + $monthlyRunRate;
                    }
                }
                $masterAccountData[$key1]['monthlyCost'] = empty($masterAccountData[$key1]['monthlyCost']) ? $totalMonthlyCost : $masterAccountData[$key1]['monthlyCost'] + $totalMonthlyCost;
                $masterAccountData[$key1]['monthlyRunRate'] = empty($masterAccountData[$key1]['monthlyRunRate']) ? $totalMonthlyRunRate : $masterAccountData[$key1]['monthlyRunRate'] + $totalMonthlyRunRate;

            }
            return array('totalMonthlyCost' => $totalMonthlyCost, 'totalMonthlyRunRate' => $totalMonthlyRunRate, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getDailyCostGraphData($start_date, $end_date, $masterAccounts, $subAccounts)
    { //Graph:: Daily Total Cost vs days
        try {
            $startDate = date('Y-m-01', strtotime($start_date));
            $endDate = date('Y-m-t', strtotime($end_date));
            $masterAccountData = [];
            $subAccountData = [];
            $dates = [];
            $dailyCosts = [];
            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        $dateRangeCost = DailyData::where('sub_account_id', $subAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                        foreach ($dateRangeCost as $key => $currentDateCost) {
                            $dates [] = $currentDateCost->date;
                            $dailyCosts [] = $currentDateCost->cost;
                        }
                        $subAccountData[$key2]['dailyCostGraphLabel'] = $dates;
                        $subAccountData[$key2]['dailyCostGraphData'] = $dailyCosts;
                        $dailyCosts = [];
                    }
                }
                $dateRangeCost = DailyData::where('master_account_id', $masterAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                foreach ($dateRangeCost as $key => $currentDateCost) {
                    $dailyCosts [] = $currentDateCost->cost;
                }
                $masterAccountData[$key1]['dailyCostGraphLabel'] = $dates;
                $masterAccountData[$key1]['dailyCostGraphData'] = $dailyCosts;
                $dailyCosts = [];

            }
            $dateRangeCost = DailyData::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
            foreach ($dateRangeCost as $key => $currentDateCost) {
                $dailyCosts [] = $currentDateCost->cost;
            }

            return array('totalDailyCostGraphLabel' => $dates, 'totalDailyCostGraphData' => $dailyCosts, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    public function getHourlyCostGraphData($start_date, $end_date, $masterAccounts, $subAccounts)
    {   //Graph:: Hourly Total Cost vs Hours
        //Sum of Total Cost Till Current Hour
        try {
            $startDate = date('Y-m-d', strtotime($start_date));
            $endDate = date('Y-m-d', strtotime('today'));//$end_date));
            $masterAccountData = [];
            $subAccountData = [];
            $hours = [];
            $hourlyCosts = [];
            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        $hourlyCost = HourlyData::where('sub_account_id', $subAccount->id)->where('date', $endDate)->orderBy('hour', 'asc')->get();
                        foreach ($hourlyCost as $key => $currentHourCost) {
                            $hours [] = $currentHourCost->hour;
                            $hourlyCosts [] = $currentHourCost->cost;
                        }
                        $subAccountData[$key2]['hourlyCostGraphLabel'] = $hours;
                        $subAccountData[$key2]['hourlyCostGraphData'] = $hourlyCosts;
                        $hourlyCosts = [];
                    }
                }
                $hourlyCost = HourlyData::where('master_account_id', $masterAccount->id)->where('date', $endDate)->orderBy('hour', 'asc')->get();
                foreach ($hourlyCost as $key => $currentHourCost) {
                    $hourlyCosts [] = $currentHourCost->cost;
                }
                $masterAccountData[$key1]['hourlyCostGraphLabel'] = $hours;
                $masterAccountData[$key1]['hourlyCostGraphData'] = $hourlyCosts;
                $hourlyCosts = [];

            }
            $hourlyCost = HourlyData::where('date', $endDate)->orderBy('hour', 'asc')->get();
            foreach ($hourlyCost as $key => $currentHourCost) {
                $hourlyCosts [] = $currentHourCost->cost;
            }

            return array('totalHourlyCostGraphLabel' => $hours, 'totalHourlyCostGraphData' => $hourlyCosts, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


}
