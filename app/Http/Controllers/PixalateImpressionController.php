<?php

namespace App\Http\Controllers;

use App\Model\PixalateImpression;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $pixalatedata = PixalateImpression::where(['date' => $date])->get();
            if (count($pixalatedata) > 0) {
                Log::channel('pixalateLog')->info('Data Already Found for Date: ' . $date . ', Api call Halted.');
                return "Data Already Found in DB, Exiting";
            }

            $client = new Client();
            $year = date("Y", strtotime($date));
            $url = "https://dashboard.api.pixalate.com/services/" . $year . "/Report/getDetails?&username=6b8549755a929a2ccfb622a3d63801f5&password=87a483160fc51023e0438d1e81db2cb6&timeZone=0&start=0&limit=100&q=kv5,impressions,sivtImpressions,sivtImpsRate,givtImpressions,givtImpsRate WHERE day>='" . $date . "' AND day<='" . $date . "' GROUP BY kv5 ORDER BY impressions DESC";
            $response = $client->request('GET', $url, ['verify' => false]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                foreach ($data['docs'] as $seatWiseData) {
                    if (empty($seatWiseData['kv5'])) continue;
                    PixalateImpression::updateOrCreate([
                        'date' => $date,
                        'seat_id' => $seatWiseData['kv5'],
                        'impressions' => $seatWiseData['impressions'],
                    ]);
                }
                Log::channel('pixalateLog')->info('Api called for Date: ' . $date . ', Api called for Date: ' . $date . ', API Call Successful, Data Created In DB.');

                return "Data Created Successfully";
            } else {
                Log::channel('pixalateLog')->warning('Api called for Date: ' . $date . ', API Call FAILED, API Client Got Error:' . $response->getStatusCode() . ' - ' . $response->getReasonPhrase());

                return "Could not get response, error code: " . $response->getStatusCode();
            }
        } catch (Exception $e) {
            Log::channel('pixalateLog')->error('Api called for Date: ' . $date . ', API Call FAILED, ' . $e->getMessage());
            $this->sendmail($e->getMessage());
            return $e->getMessage();
        }
    }

    private function sendmail($errorMessage)
    {
        try {
            $diff = round(abs(strtotime('now') - strtotime('today 4.30am')) / 60, 2);
            if ($diff < 20) {
                $subject = 'Pixalate Api Call Faced Exceptional Limits';
                Mail::send([], [], function (Message $message) use ($subject, $errorMessage) {
                    $message->to(config('admin.app.notify_email'), 'Developer');
                    $message->cc(config('admin.app.cc_email'));
                    $message->subject(config('app.name') . ' ' . $subject);
                    $message->from(config('admin.app.system_email'), config('app.name'));
                    $message->setBody($errorMessage, 'text/html');
                });
                Log::channel('pixalateLog')->error('Email Sent to: ' . config('admin.app.email') . $errorMessage);
            }
        } catch (Exception $e) {
            Log::channel('pixalateLog')->error('Failed to send Email ' . $e->getMessage());

            return $e->getMessage();
        }
    }
}
