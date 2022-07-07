<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\MasterAccount;
use App\Model\GoogleAds\SubAccount;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class FinancialReportController extends Controller
{
    /** Returns Google Ads Financial Report View
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('googleAds.financial-report.index');
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
            $startDate = date('Y-m-d', strtotime($start_date));
            $endDate = date('Y-m-d', strtotime($end_date));
            $masterAccountData = [];
            $subAccountData = [];
            $totalData = [];
            $accountInfo = [];

            foreach ($masterAccounts as $key1 => $masterAccount) {
                $masterAccountData[$key1]['id'] = $masterAccount->id;
                $masterAccountData[$key1]['account_id'] = $masterAccount->account_id;
                $masterAccountData[$key1]['name'] = $masterAccount->name;

                foreach ($subAccounts as $key2 => $subAccount) {
                    if ($subAccount->master_account_id == $masterAccount->id) {
                        $subAccountData[$key2]['id'] = $subAccount->id;
                        $subAccountData[$key2]['account_id'] = $subAccount->account_id;
                        $subAccountData[$key2]['name'] = $subAccount->name;

                        $accountInfo['master_account_name'] = $masterAccount->name;
                        $accountInfo['master_account_id'] = $masterAccount->account_id;
                        $accountInfo['sub_account_name'] = $subAccount->name;
                        $accountInfo['sub_account_id'] = $subAccount->account_id;

                        $monthlyData = DailyData::where('sub_account_id', $subAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                        $totalData = $this->processMonthlyData($monthlyData, $totalData, $accountInfo);

                        $subAccountData[$key2]['dataTableData'] = $totalData;
                        $totalData = [];
                    }
                }

                $monthlyData = DailyData::where('master_account_id', $masterAccount->id)->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                $totalData = $this->processMonthlyData($monthlyData, $totalData, null);

                $masterAccountData[$key1]['dataTableData'] = $totalData;
                $totalData = [];

            }
            foreach ($subAccountData as $singleSubAccountData){
                if(!empty($singleSubAccountData['dataTableData'])) $totalData[] = $singleSubAccountData['dataTableData'];
            }
            return array('totalData' => array_merge([],...$totalData), 'masterAccountData' => $masterAccountData, 'subAccountData' => $subAccountData);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    private function processMonthlyData($monthlyData, $totalData, $accountInfo) {
        foreach ($monthlyData as $key => $dailyData) {
            if(empty($accountInfo)){
                $subAccountData = SubAccount::where('id', $dailyData->sub_account_id)->get()->first();
                $masterAccountData = MasterAccount::where('id', $dailyData->master_account_id)->get()->first();

                $accountInfo['master_account_name'] = $masterAccountData->name;
                $accountInfo['master_account_id'] = $masterAccountData->account_id;
                $accountInfo['sub_account_name'] = $subAccountData->name;
                $accountInfo['sub_account_id'] = $subAccountData->account_id;
            }
            $totalData [] = array(
                'date' => $dailyData->date,
                'master_account_name' => $accountInfo['master_account_name'],
                'master_account_id' => $accountInfo['master_account_id'],
                'sub_account_name' => $accountInfo['sub_account_name'],
                'sub_account_id' => $accountInfo['sub_account_id'],
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
