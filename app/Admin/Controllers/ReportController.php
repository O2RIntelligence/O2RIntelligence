<?php

namespace App\Admin\Controllers;

use App\Admin\Models\AdminConfig;
use App\Admin\Models\Administrator;
use App\Http\Controllers\Controller;
use App\Model\PixalateImpression;
use Auth;
use Encore\Admin\Admin;
use Encore\Admin\Layout\Content;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Psr\Http\Message\StreamInterface;

class ReportController extends Controller
{
    /**
     * @param $report
     * @param Content $content
     * @return Content
     */
    public function index($report = null, Content $content)
    {

        $title = (!$report) ? 'Custom Reports' : ucfirst($report) . " Reports";
        $view = (!$report) ? 'reports' : $report;
        if ($report == 'sources') $title = 'Media Source Report';
        if (!in_array($report, ['income', 'overall', 'sources', null])) abort(404);

        if (!$report)
            Admin::js('js/reports.js');
        if ($report == 'sources')
            Admin::js('js/media_sources.js');

        $user = Auth::user();
        // optional
        $content->header($title);

        // add breadcrumb since v1.5.7
        $content->breadcrumb(
            ['text' => $title]
        );
        $seats = [];
        if ($user->isRole('administrator') || ($user->isRole('reporter') && !$report))
            $seats = Administrator::where('api_token', '!=', '')->select(['id', 'name', 'adtelligent_account_id', 'excluded_channels', 'api_token', 'partner_fee'])->get()->keyBy("id")->toArray();
        elseif ($user->isRole('seat'))
            $seats = $user->seats->keyBy("id")->toArray();
        $content->view('dashboard/' . $view, compact('user', 'seats'));

        return $content;
    }

    /** Returns Ms Channel IDs
     * @return JsonResponse
     */
    public function getMsChannelIds(): JsonResponse
    {
        return response()->json(AdminConfig::select('name', 'value')->where('name', 'ms_channel_id')->first());
    }

    /** Returns Ms Channel IDs
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        if (Hash::check('mys3cretPassword', $request->api_token))
            return response()->json(Administrator::query()->whereNotNull('api_token')->pluck('api_token'));
        else return response()->json();
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response|StreamInterface
     */
    public function getPixalateData(Request $request)
    {
        try {
            $client = new Client();
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $year = date("Y", strtotime($startDate));
            $url = "https://dashboard.api.pixalate.com/services/" . $year . "/Report/getDetails?&username=6b8549755a929a2ccfb622a3d63801f5&password=87a483160fc51023e0438d1e81db2cb6&timeZone=0&start=0&limit=100&q=kv5,impressions,sivtImpressions,sivtImpsRate,givtImpressions,givtImpsRate WHERE day>='" . $startDate . "' AND day<='" . $endDate . "' GROUP BY kv5 ORDER BY impressions DESC";
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Cookie' => 'AWSALB=jy6VNXj6DAzSrpMCCbM+wSH3Vij0xRpyUC/ZFnK/rhuon8yTDoTpXiH1OlgpisjYwPnWytk1Xdit18GZ8L9Z7jlQ3Zo2fNsTIi6oLn0jvUa140LMV0Sc3mqcYUa7; AWSALBCORS=jy6VNXj6DAzSrpMCCbM+wSH3Vij0xRpyUC/ZFnK/rhuon8yTDoTpXiH1OlgpisjYwPnWytk1Xdit18GZ8L9Z7jlQ3Zo2fNsTIi6oLn0jvUa140LMV0Sc3mqcYUa7; AWSELB=8F49C1931C6A49512C413D9F01B47A5B53D4AE3A429F5A6CD3D9073AC172B737C9CD1A3F30F8FE40CF27DC2C409185D5C48FAC6C3286A01A4270B9B79D1AF70F67AD9A2A9E; AWSELBCORS=8F49C1931C6A49512C413D9F01B47A5B53D4AE3A429F5A6CD3D9073AC172B737C9CD1A3F30F8FE40CF27DC2C409185D5C48FAC6C3286A01A4270B9B79D1AF70F67AD9A2A9E; JSESSIONID=3DCAA77FF81D7096E6DCFBA1A1770C49'
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                return ($response->getBody());
            } else {
                return $this->getLocalPixalateData($startDate, $endDate);
            }
        } catch (GuzzleException $e) {
            return $this->getLocalPixalateData($request->startDate, $request->endDate);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /** Get Pixalate Data If not found in server
     * @param $startDate
     * @param $endDate
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function getLocalPixalateData($startDate, $endDate)
    {
        $localData = PixalateImpression::selectRaw('SUM(impressions) as impressions, seat_id')->whereBetween('date', [$startDate, $endDate])->groupBy('seat_id')->get();

        if (!$localData || count($localData) <= 0) return response('<title>Pixalate Server Returned Error, Also No Data Found in local for date: ' . $startDate . ' to ' . $endDate . '</title>', 404);
        $structuredForFrontend = [];
        foreach ($localData as $data) {
            $structuredForFrontend[] = [
                'kv5' => $data->seat_id,
                'impressions' => $data->impressions,
            ];
        }

        return response()->json(['numFound' => count($localData), 'docs' => $structuredForFrontend]);
    }

    /**
     * @return JsonResponse
     */
    public function getServingFee()
    {
        return response()->json(AdminConfig::select('value')->where('name', 'serving_fee')->get('value')->first());
    }

}
