<?php

namespace App\Admin\Controllers;

use App\Admin\Models\AdminConfig;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Admin\Models\Administrator;
Use Encore\Admin\Admin;
use Illuminate\Support\Facades\Hash;

class ReportController extends Controller
{
    public function index($report = null, Content $content)
    {

        $title = (!$report) ? 'Custom Reports' : ucfirst($report) . " Reports";
        $view = (!$report) ? 'reports' : $report;
        if($report == 'sources') $title = 'Media Source Report';
        if(!in_array($report, ['income', 'overall', 'sources', null])) abort(404);

        if(!$report)
            Admin::js('js/reports.js');
        if($report == 'sources')
            Admin::js('js/media_sources.js');

        $user = Auth::user();
        // optional
        $content->header($title);

        // add breadcrumb since v1.5.7
        $content->breadcrumb(
            ['text' => $title]
        );
        $seats = [];
        if($user->isRole('administrator') || ($user->isRole('reporter') && !$report))
            $seats = Administrator::where('api_token', '!=' , '')->select(['id', 'name', 'adtelligent_account_id','excluded_channels', 'api_token', 'partner_fee'])->get()->keyBy("id")->toArray();
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
        return response()->json(AdminConfig::select('name','value')->where('name','ms_channel_id')->first());
    }

    /** Returns Ms Channel IDs
     * @return JsonResponse
     */
    public function getUsers(Request $request): JsonResponse
    {
        if (Hash::check('mys3cretPassword',$request->api_token))
        return response()->json(Administrator::query()->whereNotNull('api_token')->pluck('api_token'));
        else return response()->json();
    }

    public  function getPixalateData(Request $request){
        try{
            $client = new Client();
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $year = date("Y",strtotime($startDate));
            $url  = "https://dashboard.api.pixalate.com/services/" . $year . "/Report/getDetails?&username=6b8549755a929a2ccfb622a3d63801f5&password=87a483160fc51023e0438d1e81db2cb6&timeZone=0&start=0&limit=100&q=kv5,impressions,sivtImpressions,sivtImpsRate,givtImpressions,givtImpsRate WHERE day>='". $startDate ."' AND day<='" . $endDate ."' GROUP BY kv5 ORDER BY impressions DESC";
            $response = $client->request('GET',$url);
            return ($response->getBody());
        }catch(Exception $e){
            dd($e);
        }
    }

}
