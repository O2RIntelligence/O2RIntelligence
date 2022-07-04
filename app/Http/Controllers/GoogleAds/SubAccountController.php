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
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\V10\Resources\Customer;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubAccountController extends Controller
{
    public function getGoogleAdsService(){
        return new GoogleAdsService();
    }
    public function getGoogleAdsAuthService(){
        return new AuthService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|void
     */
    public function showAll()
    {
        try {
            if($subAccounts = SubAccountResource::collection(SubAccount::all())) {
                return response()->json(['success' => true, 'data' => $subAccounts]);
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * Gets all the sub-accounts of all the master accounts
     *
     * @return JsonResponse
     */
    public function getSubAccountsFromGoogleAds(): JsonResponse
    {
        try {
            $masterAccounts =  MasterAccountResource::collection(MasterAccount::where('is_online', true)->get());
            $subAccounts = [];
            foreach ($masterAccounts as $key => $masterAccount) {
                $googleAdsClient = $this->getGoogleAdsAuthService()->getGoogleAdsService($masterAccount);
                $subAccounts[] = $this->getGoogleAdsService()->getAccountTree($masterAccount,$googleAdsClient);
                $subAccounts = array_merge(...$subAccounts);
                foreach ($subAccounts as $subAccount){
                    if(!$subAccount['manager'] && $subAccount['level'] > 0) {
                        $data['master_account_id'] = $masterAccount->id;
                        $data['name'] = $subAccount['descriptiveName'];
                        $data['account_id'] = $subAccount['id'];
                        $data['timezone'] = $subAccount['timeZone'];
                        $data['currency_code'] = $subAccount['currencyCode'];
                        $data['is_active'] = true;
                        $data['is_online'] = true;
                        $this->store($data);
                    }
                }
            }

            return response()->json(['success'=>true]);
        } catch (ApiException|ValidationException|Exception $exception) {
            dd($exception);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $data
     * @return JsonResponse
     */
    private function store($data)
    {
        try {
            $subAccount = SubAccount::create([
                'master_account_id' => $data['master_account_id'],
                'name' => $data['name'],
                'account_id' => $data['account_id'],
                'timezone' => $data['timezone'],
                'currency_code' => $data['currency_code'],
                'is_active' => $data['is_active'],
                'is_online' => $data['is_online']]);
            return $subAccount->id;
        } catch (Exception $exception) {
            dd($exception);
        }
    }





}
