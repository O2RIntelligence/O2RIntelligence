<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoogleAds\SubAccountResource;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\HourlyData;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Returns Google Ads Dashboard View
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('googleAds.dashboard');
    }

    /** Gets all Dashboard Data, grouped as All, MasterAccount->correspondingData, MasterAccount->subAccount->correspondingData
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getAllDashboardData(Request $request)
    {
        try {
            $startDate = date('Y-m-d', strtotime($request->startDate ?? 'today'));
            $endDate = date('Y-m-d', strtotime($request->endDate ?? 'today'));
            $accountInformation = $this->getAccountInformation();
            $dailyProjection = $this->getDailyCostRunRateData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $monthlyProjection = $this->getMonthlyCostAndRunRateData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $dailyCostGraphData = $this->getDailyCostGraphData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $hourlyCostGraphData = $this->getHourlyCostGraphData($accountInformation['masterAccounts'], $accountInformation['subAccounts']);

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

    /** Gets All Master and Respected Sub Account Information
     * For General/Master/Sub Account wise Selection Field
     * @return array|void
     */
    public function getAccountInformation()
    {
        try {
            $masterAccounts = MasterAccount::select('id', 'name', 'account_id', 'discount', 'revenue_conversion_rate')->where('is_active', true)->where('is_online', true)->get();
            $subAccounts = SubAccountResource::collection(SubAccount::where('is_active', true)->where('is_online', true)->get());
            return array('masterAccounts' => $masterAccounts, 'subAccounts' => $subAccounts);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Daily Projection Card (Total Cost + Run Rate)
     * Daily Total Cost: Sum of Total Cost Today/Till Today
     * Daily Run Rate:  Today Spent/ today Hours * 24
     * @param $date
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
    public function getDailyCostRunRateData($start_date, $end_date, $masterAccounts, $subAccounts)
    { //Sum of Total Cost Today/Till Today
        try {
//            $dayCount = date('d', strtotime($date));
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            $difference = $date2->diff($date1);

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

                        $dataFromDate = DailyData::where('sub_account_id', $subAccount->id)->whereBetween('date', [$start_date, $end_date])->sum('cost');
                        $dailyCost = $dataFromDate;

                        if (date('Y-m-d') == $start_date) {
                            $hourlyData = HourlyData::where('sub_account_id', $subAccount->id)->sum('cost');
                            if (intval(HourlyData::count()) > 0) $hourlyDataCount = HourlyData::count();
                            else $hourlyDataCount = 1;
                            $dailyRunRate = ($hourlyData / $hourlyDataCount) * 24;
                        } else {
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
            if ($difference->days > 1) {
                $totalDailyCost = $totalDailyCost / ($difference->days + 1);
                $totalDailyRunRate = $totalDailyRunRate / ($difference->days + 1);
            }
            return array('totalDailyCost' => $totalDailyCost, 'totalDailyRunRate' => $totalDailyRunRate, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Monthly Projection Card (Total Cost + Run Rate)
     * Monthly Total Cost: Sum of Total Cost This Month
     * Monthly Run Rate: Spent(cost)/ thisMonth.days.count* Months Days
     * @param $start_date
     * @param $end_date
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
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
                        $monthlyRunRate = ($monthlyCost / ($currentDayCount+1)) * $totalDaysThisMonth;
                        $totalMonthlyRunRate += $monthlyRunRate;

                        $subAccountData[$key2]['monthlyCost'] = empty($subAccountData[$key2]['monthlyCost']) ? $monthlyCost : $subAccountData[$key2]['monthlyCost'] + $monthlyCost;
                        $subAccountData[$key2]['monthlyRunRate'] = empty($subAccountData[$key2]['monthlyRunRate']) ? $monthlyRunRate : $subAccountData[$key2]['monthlyRunRate'] + $monthlyRunRate;
                    }
                }
                $masterAccountData[$key1]['monthlyCost'] = empty($masterAccountData[$key1]['monthlyCost']) ? $totalMonthlyCost : $masterAccountData[$key1]['monthlyCost'] + $totalMonthlyCost;
                $masterAccountData[$key1]['monthlyRunRate'] = empty($masterAccountData[$key1]['monthlyRunRate']) ? $totalMonthlyRunRate : $masterAccountData[$key1]['monthlyRunRate'] + $totalMonthlyRunRate;

            }

            $startMonth = date('m', strtotime($start_date));
            $endMonth = date('m', strtotime($end_date));
            $monthDiff = intval($endMonth) - intval($startMonth);
            $isPastMonth = (intval(date('m')) - intval($startMonth) > 0);
            if ($isPastMonth) {
                $totalMonthlyCost = $totalMonthlyCost / ($monthDiff + 1);
                $totalMonthlyRunRate = $totalMonthlyCost;
            }
            return array('totalMonthlyCost' => $totalMonthlyCost, 'totalMonthlyRunRate' => $totalMonthlyRunRate, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Graph:: Daily Total Cost vs days
     * Daily Total Cost: Sum of Total Cost Today/Till Today
     * Daily Run Rate:  Today Spent/ today Hours * 24
     * @param $start_date
     * @param $end_date
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
    public function getDailyCostGraphData($start_date, $end_date, $masterAccounts, $subAccounts)
    { //Graph:: Daily Total Cost vs days
        try {
            $startDate = date('Y-m-d', strtotime($start_date));
            $endDate = date('Y-m-d', strtotime($end_date));
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
//                        $subAccountData[$key2]['dailyCostGraphLabel'] = $dates;
                        $subAccountData[$key2]['data'] = $dailyCosts;
                        $dailyCosts = [];
                    }
                }
                $dateRangeCost = DailyData::where('master_account_id', $masterAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                foreach ($dateRangeCost as $key => $currentDateCost) {
                    $dailyCosts [] = $currentDateCost->cost;
                }
//                $masterAccountData[$key1]['dailyCostGraphLabel'] = $dates;
                $masterAccountData[$key1]['data'] = $dailyCosts;
                $dailyCosts = [];

            }
            $dateRangeCost = DailyData::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
            foreach ($dateRangeCost as $key => $currentDateCost) {
                $dailyCosts [] = $currentDateCost->cost;
            }

            return array('label' => $dates, 'data' => $dailyCosts, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Graph:: Hourly Total Cost vs Hours
     * Hourly Cost: Sum of Total Cost Till Current Hour
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
    public function getHourlyCostGraphData($masterAccounts, $subAccounts)
    {   //Graph:: Hourly Total Cost vs Hours
        //Sum of Total Cost Till Current Hour
        try {
            $date = date('Y-m-d');
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

                        $hourlyCost = HourlyData::where('sub_account_id', $subAccount->id)->where('date', $date)->orderBy('hour', 'asc')->get();
                        foreach ($hourlyCost as $key => $currentHourCost) {
                            $hours [] = $currentHourCost->hour;
                            $hourlyCosts [] = $currentHourCost->cost;
                        }
//                        $subAccountData[$key2]['hourlyCostGraphLabel'] = $hours;
                        $subAccountData[$key2]['data'] = $hourlyCosts;
                        $hourlyCosts = [];
                    }
                }
                $hourlyCost = HourlyData::where('master_account_id', $masterAccount->id)->where('date', $date)->orderBy('hour', 'asc')->get();
                foreach ($hourlyCost as $key => $currentHourCost) {
                    $hourlyCosts [] = $currentHourCost->cost;
                }
//                $masterAccountData[$key1]['hourlyCostGraphLabel'] = $hours;
                $masterAccountData[$key1]['data'] = $hourlyCosts;
                $hourlyCosts = [];

            }
            $hourlyCost = HourlyData::where('date', $date)->orderBy('hour', 'asc')->get();
            foreach ($hourlyCost as $key => $currentHourCost) {
                $hourlyCosts [] = $currentHourCost->cost;
            }

            return array('label' => $hours, 'data' => $hourlyCosts, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }


}
