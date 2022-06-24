<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\HourlyData;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityReportController extends Controller
{
    /** Returns Google Ads Activity Report View
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('googleAds.activity-report');
    }

    /**Returns DashboardController
     * @return DashboardController
     */
    public function getDashboard(): DashboardController
    {
        return new DashboardController();
    }

    /** Gets all Activity Report Data, grouped as All, MasterAccount->correspondingData, MasterAccount->subAccount->correspondingData
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getAllActivityReportData(Request $request)
    {
        try {
            $startDate = date('Y-m-d', strtotime($request->startDate ?? 'today'));
            $endDate = date('Y-m-d', strtotime($request->endDate ?? 'today'));
            $accountInformation = $this->getDashboard()->getAccountInformation();
            $hourlyCostChartData = $this->getHourlyCostGraphData($accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $donutChartData = $this->getDashboard()->getMonthlyCostAndRunRateData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);
            $monthlyForecastDatatableData = $this->getMonthlyForecastDatatableData($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);

            return response()->json([
                'accountInformation' => $accountInformation,
                'hourlyCostChartData' => $hourlyCostChartData,
                'donutChartData' => $donutChartData,
                'monthlyForecastData' => $monthlyForecastDatatableData,
            ]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }


    /**Line Chart:: Hourly Cost vs Hours
     * Sum of total Cost in USD Till Current Hour
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
    public function getHourlyCostGraphData($masterAccounts, $subAccounts)
    {
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
                            $hourlyCosts [] = $currentHourCost->cost_usd;
                        }
                        $subAccountData[$key2]['hourlyCostGraphLabel'] = $hours;
                        $subAccountData[$key2]['hourlyCostGraphData'] = $hourlyCosts;
                        $hourlyCosts = [];
                    }
                }
                $hourlyCost = HourlyData::where('master_account_id', $masterAccount->id)->where('date', $date)->orderBy('hour', 'asc')->get();
                foreach ($hourlyCost as $key => $currentHourCost) {
                    $hourlyCosts [] = $currentHourCost->cost_usd;
                }
                $masterAccountData[$key1]['hourlyCostGraphLabel'] = $hours;
                $masterAccountData[$key1]['hourlyCostGraphData'] = $hourlyCosts;
                $hourlyCosts = [];

            }
            $hourlyCost = HourlyData::where('date', $date)->orderBy('hour', 'asc')->get();
            foreach ($hourlyCost as $key => $currentHourCost) {
                $hourlyCosts [] = $currentHourCost->cost_usd;
            }

            return array('totalHourlyCostChartLabel' => $hours, 'totalHourlyCostChartData' => $hourlyCosts, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }





}
