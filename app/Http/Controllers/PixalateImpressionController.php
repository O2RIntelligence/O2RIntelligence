<?php

namespace App\Http\Controllers;

use App\Model\PixalateImpression;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PixalateImpressionController extends Controller
{

    public function insertRawPixalateData(Request $request)
    {
        try {
            $inputData = $request->all();
            $insertableData = [];
            foreach ($inputData['docs'] as $seatData) {
                $insertableData[] = [
                    'id' => Str::uuid(),
                    'date' => $seatData['day'],
                    'seat_id' => $seatData['kv5'],
                    'impressions' => $seatData['impressions'],
                ];
            }
            $result = PixalateImpression::insert($insertableData);
            return response()->json($result);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
