<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Model\GoogleAds\DailyData;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    /** Returns Google Ads Financial Report View
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('googleAds.financial-report');
    }

    /**Returns DashboardController
     * @return DashboardController
     */
    public function getDashboard(): DashboardController
    {
        return new DashboardController();
    }

    /** Gets all Financial Report Data, grouped as All, MasterAccount->correspondingData, MasterAccount->subAccount->correspondingData
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getAllFinancialReportData(Request $request)
    {
        try {
            $startDate = date('Y-m-d', strtotime($request->startDate ?? 'today'));
            $endDate = date('Y-m-d', strtotime($request->endDate ?? 'today'));
            $accountInformation = $this->getDashboard()->getAccountInformation();
            $financialInformation = $this->getFinancialInformation($startDate, $endDate, $accountInformation['masterAccounts'], $accountInformation['subAccounts']);

            return response()->json([
                'accountInformation' => $accountInformation,
                'financialInformation' => $financialInformation,
            ]);
        } catch (Exception $exception) {
            dd($exception);
        }

    }


    /**Datatable:: Financial Information Datatable Data
     * @param $start_date
     * @param $end_date
     * @param $masterAccounts
     * @param $subAccounts
     * @return array|void
     */
    public function getFinancialInformation($start_date, $end_date, $masterAccounts, $subAccounts)
    {
        try {
            $startDate = date('Y-m-01', strtotime($start_date));
            $endDate = date('Y-m-t', strtotime($end_date));
            $masterAccountData = [];
            $subAccountData = [];
            $totalData = [];
            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        $monthlyData = DailyData::where('sub_account_id', $subAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                        $totalData = $this->processMonthlyData($monthlyData, $totalData);

                        $subAccountData[$key2]['dataTableData'] = $totalData;
                        $totalData = [];
                    }
                }

                $monthlyData = DailyData::where('master_account_id', $masterAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                $totalData = $this->processMonthlyData($monthlyData, $totalData);

                $masterAccountData[$key1]['dataTableData'] = $totalData;
                $totalData = [];

            }
            $monthlyData = DailyData::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
            $totalData = $this->processMonthlyData($monthlyData, $totalData);

            return array('totalData' => $totalData, 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    private function processMonthlyData($monthlyData, $totalData){
        foreach ($monthlyData as $key => $dailyData) {
            $totalData [] = array(
                'date' => $dailyData->date,
                'spent_in_ars' => $dailyData->cost,
                'spent_in_usd' => $dailyData->cost_usd,
                'discount' => $masterAccount->discount??0,
                'revenue' => $dailyData->revenue??0,
                'google_media_cost' => $dailyData->google_media_cost??0,
                'plus_m_share' => $dailyData->plus_m_share??0,
                'total_cost' => $dailyData->total_cost??0,
                'net_income' => $dailyData->net_income??0,
                'net_income_percent' => $dailyData->net_income_percent??0,
            );
        }
        return $totalData;
    }


}
