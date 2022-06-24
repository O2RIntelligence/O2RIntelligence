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





}
