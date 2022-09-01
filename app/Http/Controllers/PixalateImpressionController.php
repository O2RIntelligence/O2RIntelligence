<?php

namespace App\Http\Controllers;

use App\Model\PixalateImpression;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PixalateImpressionController extends Controller
{

    public function insertRawPixalateData(Request $request)
    {
        try {
            if ($request->pass != "Wytk1Xdit18GZ8L9Z7jlQ3Zo2fNsTIi6oLn") return 0;
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

    public function getPreviousPixalateData($date)
    {
        try {
            $client = new Client();
            $year = date("Y", strtotime($date));
            $url = "https://dashboard.api.pixalate.com/services/" . $year . "/Report/getDetails?&username=6b8549755a929a2ccfb622a3d63801f5&password=87a483160fc51023e0438d1e81db2cb6&timeZone=0&start=0&limit=100&q=kv5,impressions,sivtImpressions,sivtImpsRate,givtImpressions,givtImpsRate WHERE day>='" . $date . "' AND day<='" . $date . "' GROUP BY kv5 ORDER BY impressions DESC";
            $response = $client->request('GET', $url, [
                'verify' => false,
                'headers' => [
                    'Cookie' => 'AWSALB=jy6VNXj6DAzSrpMCCbM+wSH3Vij0xRpyUC/ZFnK/rhuon8yTDoTpXiH1OlgpisjYwPnWytk1Xdit18GZ8L9Z7jlQ3Zo2fNsTIi6oLn0jvUa140LMV0Sc3mqcYUa7; AWSALBCORS=jy6VNXj6DAzSrpMCCbM+wSH3Vij0xRpyUC/ZFnK/rhuon8yTDoTpXiH1OlgpisjYwPnWytk1Xdit18GZ8L9Z7jlQ3Zo2fNsTIi6oLn0jvUa140LMV0Sc3mqcYUa7; AWSELB=8F49C1931C6A49512C413D9F01B47A5B53D4AE3A429F5A6CD3D9073AC172B737C9CD1A3F30F8FE40CF27DC2C409185D5C48FAC6C3286A01A4270B9B79D1AF70F67AD9A2A9E; AWSELBCORS=8F49C1931C6A49512C413D9F01B47A5B53D4AE3A429F5A6CD3D9073AC172B737C9CD1A3F30F8FE40CF27DC2C409185D5C48FAC6C3286A01A4270B9B79D1AF70F67AD9A2A9E; JSESSIONID=3DCAA77FF81D7096E6DCFBA1A1770C49'
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                foreach ($data['docs'] as $seatWiseData) {
                    PixalateImpression::updateOrCreate([
                        'date' => $date,
                        'seat_id' => $seatWiseData['kv5'],
                        'impressions' => $seatWiseData['impressions'],
                    ]);
                }var_dump($data['numFound']);
                return "Data Created Successfully";
            } else {
                return "Could not get response, error code: " . $response->getStatusCode();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
