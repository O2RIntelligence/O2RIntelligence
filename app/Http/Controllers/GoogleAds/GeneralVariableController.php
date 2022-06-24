<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGeneralVariableRequest;
use App\Http\Requests\UpdateGeneralVariableRequest;
use App\Http\Resources\GoogleAds\GeneralVariableResource;
use App\Model\GoogleAds\GeneralVariable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GeneralVariableController extends Controller
{
    public function index()
    {
        return view('googleAds.general-variable.index');
    }

    /**Stores a General Variable
     * @param StoreGeneralVariableRequest $request
     * @return JsonResponse
     */
    public function store(StoreGeneralVariableRequest $request)
    {
        try {
            $generalVariables = $request->validated();
            if(GeneralVariable::create($generalVariables)){
                return response()->json(array(['success' => true]));
            }else return response()->json(array(['success' => false]));
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /** Shows a specific General Variable from provided General Variable id
     * @param UpdateGeneralVariableRequest $request
     * @return JsonResponse|void
     */
    public function show(UpdateGeneralVariableRequest $request)
    {
        try {
            if($generalVariables = GeneralVariable::where('id', $request->id)->get()->first()) {
                return response()->json(['success' => true, 'data' => new GeneralVariableResource($generalVariables)]);
            }else return response()->json(['success' => false, 'message' => 'No Data Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Shows All General Variables
     * @return JsonResponse
     */
    public function getAll()
    {
        try {
            if($generalVariables = GeneralVariableResource::collection(GeneralVariable::all())) {
                return response()->json(array(['success' => true, 'data' => $generalVariables]));
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /** Updates a General Variable on provided id
     * @param UpdateGeneralVariableRequest $request
     * @return JsonResponse|void
     */
    public function update(UpdateGeneralVariableRequest $request)
    {
        try {
            $generalVariables = GeneralVariable::where('id', $request->id)->get()->first();
            if ($generalVariables&&$generalVariables->update($request->validated())) {
                return response()->json(array(['success' => true]));
            }else return response()->json(['success' => false, 'message' => 'No Data Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Changes Status of a General Variable
     * @param UpdateGeneralVariableRequest $request
     * @return JsonResponse|void
     */
    public function switchStatus(UpdateGeneralVariableRequest $request)
    {
        try {
            $generalVariables = GeneralVariable::where('id', $request->id)->get()->first();
            if ($generalVariables&&$generalVariables->update([$generalVariables->is_active = !$generalVariables->is_active])) {
                return response()->json(array(['success' => true]));
            }else return response()->json(['success' => false, 'message' => 'No Data Found']);
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    /**Deletes a General Variable
     * @param UpdateGeneralVariableRequest $request
     * @return JsonResponse
     */
    public function delete(UpdateGeneralVariableRequest $request)
    {
        try {
            if(GeneralVariable::withTrashed()->where('id', $request->id)->delete()) {
                return response()->json(array(['success' => true]));
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}
