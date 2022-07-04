<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMasterAccountRequest;
use App\Http\Requests\UpdateMasterAccountRequest;
use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Model\GoogleAds\MasterAccount;
use App\Services\GoogleAds\AuthService;
use App\Services\GoogleAds\GoogleAdsService;
use Exception;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MasterAccountController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(){
        return view('googleAds.account-setting.index');
    }

    /**
     * @return SubAccountController
     */
    public function subAccountController(): SubAccountController
    {
        return new SubAccountController();
    }

    /**
     * @return GoogleAdsService
     */
    public function getGoogleAdsService(): GoogleAdsService
    {
        return new GoogleAdsService();
    }

    /**
     * @return AuthService
     */
    public function getGoogleAdsAuthService(): AuthService
    {
        return new AuthService();
    }

    /**Stores a Master Account
     * @param StoreMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function store(StoreMasterAccountRequest $request)
    {
        try {
            $masterAccountInformation = (object) $request->validated();
            if(MasterAccount::where('account_id', $masterAccountInformation->account_id)->get()->first()) {
                return response()->json(['success' => false, 'message' => 'Duplicate Entry']);
            }
            if($this->masterAccountHasAccess($masterAccountInformation)){
                $masterAccountInformation->is_active = true;
                $masterAccountInformation->is_online = true;
                $masterAccount = MasterAccount::create($masterAccountInformation);
                return response()->json(['success'=> (bool)$masterAccount]);
            }else return response()->json(['success' => false, 'message' => 'Developer Token Not Updated,Sub Accounts Under This Master Account Are Not Accessible']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /** Shows a specific Master Account from provided master account id
     * @param UpdateMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function show(UpdateMasterAccountRequest $request)
    {
        try {
            if($masterAccount = MasterAccount::where('id', $request->id)->get()->first()) {
                return response()->json(['success' => true, 'data' => new MasterAccountResource($masterAccount)]);
            }else return response()->json(['success' => false, 'message' => 'No Accounts Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Shows All Master Accounts
     * @return JsonResponse
     */
    public function getAll()
    {
        try {
            if($masterAccounts = MasterAccountResource::collection(MasterAccount::all())) {
                return response()->json(['success' => true, 'data' => $masterAccounts]);
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /** Updates a Master Account on provided id
     * @param UpdateMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function update(UpdateMasterAccountRequest $request)
    {
        try {
            $masterAccount = MasterAccount::where('id', $request->id)->get()->first();
            if ($masterAccount&&$masterAccount->update($request->validated())) {
                return response()->json(['success' => true]);
            }else return response()->json(['success' => false, 'message' => 'No Accounts Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Changes Status of a Master Account
     * @param UpdateMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function switchStatus(UpdateMasterAccountRequest $request)
    {
        try {
            $masterAccount = MasterAccount::where('id', $request->id)->get()->first();
            if ($masterAccount) {
                if ($this->masterAccountHasAccess($masterAccount)) {
                    $masterAccount->update([$masterAccount->is_active = !$masterAccount->is_active]);
                    return response()->json(['success' => true]);
                } else return response()->json(['success' => false, 'message' => 'Sub Accounts Under This Master Account Are Not Accessible']);
            } else return response()->json(['success' => false, 'message' => 'No Accounts Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Deletes a Master Account
     * @param UpdateMasterAccountRequest $request
     * @return JsonResponse
     */
    public function delete(UpdateMasterAccountRequest $request)
    {
        try {
            if(MasterAccount::withTrashed()->where('id', $request->id)->delete()) {
                return response()->json(['success' => true]);
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**
     * @param $masterAccount
     * @return void
     * @throws Exception
     */
    public function masterAccountHasAccess($masterAccount)
    {
        try {
            $googleAdsClient = $this->getGoogleAdsAuthService()->getGoogleAdsService($masterAccount);
            $subAccountInformation = $this->getGoogleAdsService()->getAccountTree($masterAccount, $googleAdsClient);
            return count($subAccountInformation) > 0;
        } catch (ApiException $exception) {
            return false;
            $e = json_decode($exception->getMessage());
            return response()->json(['success' => false, 'message' => $e->details[0]->errors[0]->message]);
        }catch (Exception $exception){
            dd($exception);
        }
    }

    /**Checks a Master Account Access is updated to Basic or Not
     * @param UpdateMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function checkIfDeveloperTokenIsUpdated(UpdateMasterAccountRequest $request)
    {
        try {
            $masterAccount = MasterAccount::where('id', $request->id)->get()->first();
            if ($masterAccount) {
                if ($this->masterAccountHasAccess($masterAccount)) {
                    $masterAccount->update([$masterAccount->is_online = true]);
                    return response()->json(['success' => true]);
                } else return response()->json(['success' => false, 'message' => 'Developer Token not Updated Yet, Sub Accounts Under This Master Account Are Still Not Accessible']);
            } else return response()->json(['success' => false, 'message' => 'No Accounts Found']);
        } catch(Exception $exception) {
            dd($exception);
        }
    }
}
