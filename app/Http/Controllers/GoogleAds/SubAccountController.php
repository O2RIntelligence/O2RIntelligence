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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubAccountController extends Controller
{
    public function getGoogleAdsService()
    {
        return new GoogleAdsService();
    }

    public function getGoogleAdsAuthService()
    {
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
            if ($subAccounts = SubAccountResource::collection(SubAccount::all())) {
                return response()->json(['success' => true, 'data' => $subAccounts]);
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * Gets all the sub-accounts of all the master accounts
     *
     * @return array
     */
    public function getSubAccountsFromGoogleAds()
    {
        try {
            $masterAccounts = MasterAccountResource::collection(MasterAccount::where('is_online', true)->where('is_active', true)->get());
            $subAccounts = [];
            $deletedAccounts = [];
            foreach ($masterAccounts as $key => $masterAccount) {
                $googleAdsClient = $this->getGoogleAdsAuthService()->getGoogleAdsService($masterAccount);
                $subAccounts[] = $this->getGoogleAdsService()->getAccountTree($masterAccount, $googleAdsClient);
                $subAccounts = array_merge(...$subAccounts);
                foreach ($subAccounts as $key => $subAccount) {
                    $subAccountEnabled = $this->getGoogleAdsService()->getSubAccountDetails($googleAdsClient, $subAccount);
                    if (!$subAccountEnabled) {
                        $deletedAccounts[] = $subAccount['id'];
                        SubAccount::where('account_id', $subAccount['id'])->delete();
                        unset($subAccounts[$key]);
                    }
                    if (!$subAccount['manager'] && $subAccount['level'] > 0 && $subAccountEnabled) {
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

            return ['success' => true, 'actions_taken' => $deletedAccounts];
        } catch (ApiException|ValidationException|Exception $exception) {
            return ['success' => false];
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $data
     * @return false
     */
    private function store($data)
    {
        try {
            if (SubAccount::where('account_id', $data['account_id'])->get()->first()) return false;
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
