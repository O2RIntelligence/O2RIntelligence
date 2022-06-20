<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMasterAccountRequest;
use App\Http\Requests\UpdateMasterAccountRequest;
use App\Http\Resources\GoogleAds\MasterAccountResource;
use App\Model\GoogleAds\MasterAccount;
use Exception;
use Illuminate\Http\JsonResponse;

class MasterAccountController extends Controller
{
    public function subAccountController(){
        return new SubAccountController();
    }

    /**Stores a Master Account
     * @param StoreMasterAccountRequest $request
     * @return JsonResponse|void
     */
    public function store(StoreMasterAccountRequest $request)
    {
        try {
            $masterAccountInformation = $request->validated();
            if(MasterAccount::create($masterAccountInformation)){
               $response = $this->subAccountController()->getSubAccountsFromGoogleAds();
                return $response;
            }else return response()->json(array(['success' => false]));
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
                return response()->json(array(['success' => true, 'data' => $masterAccounts]));
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
                return response()->json(array(['success' => true]));
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
            if ($masterAccount&&$masterAccount->update([$masterAccount->is_active = !$masterAccount->is_active])) {
                return response()->json(array(['success' => true]));
            }else return response()->json(['success' => false, 'message' => 'No Accounts Found']);
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
                return response()->json(array(['success' => true]));
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

}
